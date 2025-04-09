$(document).ready(function() {
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
    
    // Add progress bar update function
    function updateProgress() {
        const progressPercentage = (currentQuestion / totalQuestions) * 100;
        $("#progress-bar").css("width", progressPercentage + "%");
        $("#progress-text").text(Math.round(progressPercentage) + "% Complete");
    }

    window.loadQuestion = function() {
        updateProgress();
    
        $("#current-q").text(currentQuestion + 1);
    
        // Disable/enable navigation buttons
        $("#prev-btn").prop("disabled", currentQuestion === 0);
    
        if (currentQuestion === totalQuestions - 1) {
            $("#next-btn").text("Submit").removeClass("next-btn-arrow").addClass("submit-btn"); // Change the button text and add a new class
        } else {
            $("#next-btn").html('<i class="fas fa-chevron-right"></i>').removeClass("submit-btn").addClass("next-btn-arrow"); // Reset to the arrow button
        }        
    
        if (currentQuestion < totalQuestions) {
            $("#question-text").text(questions[currentQuestion]);
    
            // Remove 'selected' class from all buttons
            $(".option-btn").removeClass("selected");
    
            // Highlight the existing answer if any
            if (scores[currentQuestion] !== undefined) {
                // Mark the selected button
                $(`.option-btn[data-score="${scores[currentQuestion]}"]`).addClass("selected");
                $("#next-btn").prop("disabled", false); // Enable the next button if answered
            } else {
                $("#next-btn").prop("disabled", true); // Disable the next button if unanswered
            }
        }
    };
    

    function validatePassword() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;
        var errorText = document.getElementById("password_error");

        if (password !== confirmPassword) {
            errorText.style.display = "block";
            return false; // Prevent form submission
        } else {
            errorText.style.display = "none";
            return true; // Allow form submission
        }
    }

    // Add navigation handlers
    $("#prev-btn").click(() => {
    if (currentQuestion > 0) {
        currentQuestion--;
        loadQuestion();
    }
    });

    $("#next-btn").off("click").on("click", function (e)  {
        e.stopPropagation();

        if ($(this).hasClass("submit-btn")) {
            return; 
        }

        // Proceed to next question if answered
        if (currentQuestion < totalQuestions - 1) {
            currentQuestion++;
            loadQuestion();
        }
    });
    

    $(".option-btn").on("click", function() {
        let score = parseInt($(this).data("score"));
        scores[currentQuestion] = score; // Store score at current index
        $(this).addClass("selected").siblings().removeClass("selected");

        $("#next-btn").prop("disabled", false);
    });

    loadQuestion();
});
