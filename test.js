$(document).ready(function () {
    window.questions = [
        "1. I have trouble sleeping or staying asleep.",
        "2. I feel sad or down without a clear reason.",
        "3. I struggle to concentrate on daily tasks.",
        "4. I have lost interest in activities I used to enjoy.",
        "5. I feel physically exhausted even when I get enough rest.",
        "6. I avoid social interactions or prefer to be alone.",
        "7. I feel hopeless about the future.",
        "8. I experience sudden mood swings or irritability.",
        "9. I have a decreased or increased appetite.",
        "10. I have negative thoughts about myself (e.g., feeling worthless or like a failure).",
        "11. I experience frequent headaches or body pain without a clear cause.",
        "12. I feel guilty or blame myself for things outside my control.",
        "13. I have difficulty making decisions, even simple ones.",
        "14. I feel anxious or restless most of the time.",
        "15. I find it hard to experience joy, even in happy moments.",
        "16. I feel detached from reality or like I am 'numb.'",
        "17. I feel like my emotions are unpredictable or uncontrollable.",
        "18. I have trouble remembering things or focusing on tasks.",
        "19. I feel like I am a burden to those around me.",
        "20. I have had thoughts of self-harm or that life is meaningless."
    ];

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
            $("#next-btn").html('<i class="fas fa-chevron-right"></i>');
        }

        $("#question-text").text(questions[currentQuestion]);
        $(".option-btn").removeClass("selected");

        const selectedScore = scores[currentQuestion];
        if (selectedScore !== undefined) {
            $(`.option-btn[data-score="${selectedScore}"]`).addClass("selected");
        }

        updateNextButtonState();
    };

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

    $(".option-btn").on("click", function () {
        const score = parseInt($(this).data("score"));
        scores[currentQuestion] = score;
    
        $(this).addClass("selected").siblings().removeClass("selected");
        updateNextButtonState();
    
        // Instantly go to next question unless it's the last one
        if (currentQuestion < totalQuestions - 1) {
            currentQuestion++;
            loadQuestion();
        } else {
            // If it's the last question, change next button to "Submit"
            $("#next-btn")
                .text("Submit")
                .removeClass("next-btn-arrow")
                .addClass("submit-btn");
            // Optionally auto-submit here if you want:
            // submitAnswers();
        }
    });    
    
    loadQuestion();
});
