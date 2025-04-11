<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depression Self-Assessment Test</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../CSS/d_styles.css">
</head>
<body>
    
    <header>
        <h1>Depression Self-Assessment Test</h1>
        <h4>Are you feeling alright?</h4>
        <form method="post" action="../user_action/logout.php">
            <button type="submit" class="nav-btn" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </form>
    </header>

    <div class="container">

        <div id="loading-spinner" style="display: none;">
            <div class="spinner"></div>
            <p>Loading user data...</p>
        </div>
        <!--Question Cards-->
        <div id="question-card">

            <div id="progress-container">
                <div id="progress-bar"></div>
            </div>
            <div id="progress-text">0% Complete</div>
            
            <h2 id="question-text"></h2>
            <div id="options">
                <button class="option-btn" data-score="0">Never (0)</button>
                <button class="option-btn" data-score="1">Rarely (1)</button>
                <button class="option-btn" data-score="2">Sometimes (2)</button>
                <button class="option-btn" data-score="3">Often (3)</button>
                <button class="option-btn" data-score="4">Always (4)</button>
            </div>

            <div class="navigation-controls">
                <button class="nav-btn" id="prev-btn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span class="question-count">Question <span id="current-q">1</span>/20</span>
                <button class="nav-btn" id="next-btn">
                    <i class="fas fa-chevron-right"></i>
                </button>

            </div>
        </div>

        <div class="info">
            <h2>What is Depression and Anxiety?</h2>
            <p>
                Depression is a mood disorder that causes a persistent feeling of sadness and loss of interest. Also called major depressive disorder or clinical depression, it affects how you feel, think and behave and can lead to a variety of emotional and physical problems. You may have trouble doing normal day-to-day activities, and sometimes you may feel as if life isn't worth living.
                <br>
                <br>
            </p>
            <p>
                Experiencing occasional anxiety is a normal part of life. However, people with anxiety disorders frequently have intense, excessive and persistent worry and fear about everyday situations. Often, anxiety disorders involve repeated episodes of sudden feelings of intense anxiety and fear or terror that reach a peak within minutes (panic attacks).
                <a href="https://www.mayoclinic.org/diseases-conditions/depression/symptoms-causes/syc-20356007">Source: Mayo Clinic</a>
            </p>
        </div>

        <div class="videos_to_help">
            <h3>Helpful Resources</h3>
            <div class="video-wrapper">
                <iframe src="https://www.youtube.com/embed/7BPQq9QdECo"></iframe>
                <iframe src="https://www.youtube.com/embed/AjEZRc0dyiQ"></iframe>
                <iframe src="https://www.youtube.com/embed/zzfREEPbUsA"></iframe>
            </div>
        </div>
    </div>
    <script src="../test.js"></script>
    <script src="../answer.js"></script>
</body>
</html>