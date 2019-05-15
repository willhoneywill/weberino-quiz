<div id="weberino-app">
    <div class="container">
        <div class="row mb-3">
            <div class="col-sm">
                <h2><?php echo $title; ?>
                </h2>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm">
                <button type="button" class="btn btn-primary" v-on:click='howToPlay = !howToPlay'>How to play</button>
            </div>
        </div>
        <div class="row mb-3" v-show="howToPlay">
            <div class="col">
                <?php echo nl2br($how_to_play); ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm">
                Score <span>{{ score }}</span> / 10
            </div>

            <div class="col-sm d-flex flex-row-reverse">
                <weberino-timer :mins="mins" :seconds="seconds"></weberino-timer>
            </div>
        </div>
        <div class="row mb-3" v-show="quizFinish">
            <div class="col">
                <button type="button" class="btn btn-light" v-on:click="loadQuestions">Try Again</button>
            </div>

            <div class="col d-flex flex-row-reverse">
                <button type="button" class="btn btn-dark" v-on:click="loadAnswers">Show Answers</button>
            </div>
        </div>
        <div class="row mb-3" v-show="quizLoad">
            <div class="col-sm d-flex justify-content-center">
                <button type="button" class="btn btn-block btn-success" v-on:click="loadQuestions">Start Quiz</button>
            </div>
        </div>
        <div class="row mb-3" v-show="quizFinish">
            <div class="col-sm d-flex justify-content-center">
                <p>You did ok</p>
            </div>
        </div>
        <div class="row mb-3" v-show="quizFinish">
            <div class="col-sm d-flex justify-content-center">
                <p>You scored <span>{{ score }}</span> / 10</p>
            </div>
        </div>
        <div class="row mb-3" v-show="quizFinish">
            <div class="col-sm d-flex justify-content-center">
                <p>Share your score with your friends!</p>
            </div>
        </div>
        <div class="row mb-3" v-show="quizPlay">
            <div class="col-10">
                <input type="text" class="form-control" placeholder="Enter your answer" v-model="answer">
                <input type="hidden" v-model="quizId" name="quiz_id" value="<?php echo $atts['id']; ?>">
            </div>
            <div class="col-2  d-flex flex-row-reverse">
                <button type="button" class="btn btn-primary" v-on:click="checkAnswer">Submit</button>
            </div>
        </div>
        <div class="row mb-3" v-show="correctAnswer">
            <div class="col d-flex justify-content-center">
                <div class="alert alert-success w-100" role="alert">
                    Correct Answer!
                </div>
            </div>
        </div>
        <div class="row mb-3" v-show="wrongAnswer">
            <div class="col d-flex justify-content-center">
                <div class="alert alert-danger w-100" role="alert">
                    Wrong Answer!
                </div>
            </div>
        </div>
        <div class="row mb-3" v-show="emptyAnswer">
            <div class="col d-flex justify-content-center">
                <div class="alert alert-dark w-100" role="alert">
                    Enter an answer!
                </div>
            </div>
        </div>
        <div class="row mb-3" v-show="alreadyAnswered">
            <div class="col d-flex justify-content-center">
                <div class="alert alert-dark w-100" role="alert">
                    You've already answered that!
                </div>
            </div>
        </div>
        <div class="row mb-3 py-3 bg-light">
            <div class="col-sm">
                Rank
            </div>
            <div class="col-sm">
                Hint
            </div>
            <div class="col-sm d-flex flex-row-reverse">
                Answer
            </div>
        </div>
        <div class="row border-bottom py-1" v-for="(question, key, index) in questions">
            <div class="col-1">
                {{ question.id }}
            </div>
            <div class="col-6">
                {{ question.question }}
            </div>
            <div class="col-5 d-flex flex-row-reverse">
                <span :ref="'answer'+key" ></span>
            </div>
        </div>
    </div>

</div>