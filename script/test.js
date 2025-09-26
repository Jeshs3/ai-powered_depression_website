// test.js
$(document).ready(function () {
    // Fetch question.json
    fetch('/BANOL6/dataset/question.json')
        .then(response => {
            if (!response.ok) throw new Error('Failed to load JSON');
            return response.json();
        })
        .then(data => {
            console.log('Questions loaded', data);
            window.questions = data;
            window.currentQuestion = 0;
            window.scores = [];
            window.totalQuestions = questions.length;

            function updateProgress() {
                const percent = (currentQuestion / totalQuestions) * 100;
                $("#progress-bar").css("width", percent + "%");
                $("#progress-text").text(Math.round(percent) + "% Completed");
            }

            function updateNextButtonState() {
                const answered = scores[currentQuestion] !== undefined;
                $("#next-btn").prop("disabled", !answered);
            }

            // Helper: extract emoji and label from the "label" field (e.g. "😴 Restless")
            function splitEmojiAndText(fullLabel) {
                // simple split at first whitespace (works for your current JSON)
                const firstSpace = fullLabel.indexOf(' ');
                if (firstSpace === -1) return { emoji: fullLabel, text: '' };
                return {
                    emoji: fullLabel.slice(0, firstSpace),
                    text: fullLabel.slice(firstSpace + 1).trim()
                };
            }

            window.loadQuestion = function () {
                updateProgress();

                $("#current-q").text(currentQuestion + 1);
                $("#prev-btn").prop("disabled", currentQuestion === 0);

                const isLast = currentQuestion === totalQuestions - 1;

                // Next / Submit button text toggling
                if (!isLast) {
                    $("#next-btn")
                        .html('<i class="fas fa-chevron-right"></i>')
                        .removeClass("submit-btn")
                        .addClass("next-btn-arrow");
                } else {
                    $("#next-btn")
                        .text("Continue")
                        .addClass("submit-btn")
                        .removeClass("next-btn-arrow");
                }

                // Update the question text
                $("#question-text").text(questions[currentQuestion].text);

                // Clear old options
                $("#options").empty();

                // Build a 5-column grid driven by emotions array
                const currentOptions = questions[currentQuestion].emotions;

                // Create accessible container with role="radiogroup"
                const $grid = $('<div class="likert-grid" role="radiogroup" aria-label="Likert scale"></div>');

                currentOptions.forEach((emotion, index) => {
                    const { emoji, text } = splitEmojiAndText(emotion.label);

                    const $col = $(`
                        <div class="likert-col" data-index="${index}" data-value="${emotion.value}" role="radio" aria-checked="false" tabindex="0">
                            <div class="emoji" aria-hidden="true">${emoji}</div>
                            <div class="label">${text}</div>
                            <button class="radio-btn" aria-hidden="true" title="${emotion.value}"></button>
                        </div>
                    `);

                    // Restore previous selection UI if exists
                    if (scores[currentQuestion] === index) {
                        $col.addClass('selected').attr('aria-checked', 'true');
                    }

                    // Click handler
                    $col.on('click keypress', function (ev) {
                        // allow Enter/Space or Click
                        if (ev.type === 'keypress' && !(ev.which === 13 || ev.which === 32)) return;

                        const idx = Number($(this).attr('data-index'));
                        scores[currentQuestion] = idx;

                        // mark selected visually / accessibility attributes
                        $grid.find('.likert-col').removeClass('selected').attr('aria-checked', 'false');
                        $(this).addClass('selected').attr('aria-checked', 'true');

                        updateNextButtonState();

                        // Auto-advance if not last
                        if (currentQuestion < totalQuestions - 1) {
                            currentQuestion++;
                            loadQuestion();
                        } else {
                            // last question selected -> prepare submit state
                            $("#next-btn")
                                .text("Submit")
                                .removeClass("next-btn-arrow")
                                .addClass("submit-btn");
                        }
                    });

                    // keyboard focus styling: handle focus + Enter/Space through keypress binding above
                    $col.on('keydown', function (e) {
                        // left/right arrow navigation inside grid
                        if (e.key === 'ArrowRight') {
                            e.preventDefault();
                            const next = $(this).next('.likert-col');
                            if (next.length) next.focus();
                        } else if (e.key === 'ArrowLeft') {
                            e.preventDefault();
                            const prev = $(this).prev('.likert-col');
                            if (prev.length) prev.focus();
                        }
                    });

                    $grid.append($col);
                });

                $("#options").append($grid);

                updateNextButtonState();
            };

            // Navigation Handlers
            $("#prev-btn").off('click').on("click", function () {
                if (currentQuestion > 0) {
                    currentQuestion--;
                    loadQuestion();
                }
            });

            $("#next-btn").off("click").on("click", function (e) {
                e.stopPropagation();

                if ($(this).hasClass("submit-btn")) {
                    // submitAnswers should be defined in answer.js as before
                    if (typeof window.submitAnswers === 'function') {
                        window.submitAnswers();
                    } else {
                        console.warn('submitAnswers() not found.');
                    }
                    return;
                }

                if (currentQuestion < totalQuestions - 1) {
                    currentQuestion++;
                    loadQuestion();
                }
            });

            // Initial load
            loadQuestion();
        })
        .catch(error => {
            console.error("Failed to load questions.json:", error);
            $("#question-text").text("Failed to load questions. Please try again later.");
        });
});
