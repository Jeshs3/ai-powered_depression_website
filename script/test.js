$(document).ready(function () {
    // Fetch question.json
    fetch('/BANOL6/script/question.json')
        .then(response => 
            {
                if (!response.ok) throw new Error('Failed to load JSON');
                return response.json();
            }
        )
        .then(data => {
            console.log(data);
            window.questions = data;
            window.currentQuestion = 0;
            window.scores = [];
            window.totalQuestions = questions.length;

            function updateProgress() {
                const percent = (currentQuestion / totalQuestions) * 100;
                $("#progress-bar").css("width", percent + "%");
                $("#progress-text").text(Math.round(percent) + "% Complete");
            }

            function updateNextButtonState() {
                const answered = scores[currentQuestion] !== undefined;
                $("#next-btn").prop("disabled", !answered);
            }

            window.loadQuestion = function () {
                updateProgress();
            
                $("#current-q").text(currentQuestion + 1);
                $("#prev-btn").prop("disabled", currentQuestion === 0);
            
                const isLast = currentQuestion === totalQuestions - 1;
            
                $("#next-btn")
                    .text(isLast ? "Submit" : "")
                    .toggleClass("submit-btn", isLast)
                    .toggleClass("next-btn-arrow", !isLast);
            
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
            
                // Dynamically generate option buttons from emotions array
                const currentOptions = questions[currentQuestion].emotions;
            
                currentOptions.forEach((emotions, index) => {
                    const button = $(`<button class="option-btn" data-score="${index}">${emotions.label} (${index})</button>`);
            
                    // If already selected before, mark as selected
                    if (scores[currentQuestion] === index) {
                        button.addClass("selected");
                    }
            
                    // Click handler for selecting score
                    button.on("click", function () {
                        scores[currentQuestion] = index;
            
                        $(this).addClass("selected").siblings().removeClass("selected");
                        updateNextButtonState();
            
                        if (currentQuestion < totalQuestions - 1) {
                            currentQuestion++;
                            loadQuestion();
                        } else {
                            $("#next-btn")
                                .text("Submit")
                                .removeClass("next-btn-arrow")
                                .addClass("submit-btn");
                        }
                    });
            
                    $("#options").append(button);
                });
            
                updateNextButtonState();
            };            

            //Navigation Handlers
            $("#prev-btn").click(function () {
                if (currentQuestion > 0) {
                    currentQuestion--;
                    loadQuestion();
                }
            });

            $("#next-btn").off("click").on("click", function (e) {
                e.stopPropagation();

                if ($(this).hasClass("submit-btn")) {
                    window.submitAnswers();
                    return;
                }

                if (currentQuestion < totalQuestions - 1) {
                    currentQuestion++;
                    loadQuestion();
                }
            });

            loadQuestion();
        })
        .catch(error => {
            console.error("Failed to load questions.json:", error);
            $("#question-text").text("Failed to load questions. Please try again later.");
        });
});
