<div id="fb-root"></div>
<div id="weberino-app">
    <div class="weberino-grid-container">

            <div class="weberino-grid-item weberino-one-col">
                <h2><?php echo $title; ?>
                </h2>
            </div>


            <div class="weberino-grid-item  weberino-one-col">
                <button type="button" class="weberino-btn weberino-btn-primary" v-on:click='howToPlay = !howToPlay'>How to play</button>
            </div>


            <div class="weberino-grid-item  weberino-one-col" v-show="howToPlay">
				<?php echo nl2br($how_to_play); ?>
            </div>

            <div class="weberino-grid-item weberino-two-col">
                Score <span class="weberino-bold weberino-lead">
                        <span>{{ score }}</span> / 10
                      </span>
            </div>

            <div class="weberino-grid-item weberino-two-col weberino-right">
                <weberino-timer :mins="mins" :seconds="seconds"></weberino-timer>
            </div>


            <div class="weberino-grid-item weberino-two-col" v-show="quizFinish">
                <button type="button" class="weberino-btn weberino-btn-light" v-on:click="playAgain">Try Again</button>
            </div>

            <div class="weberino-grid-item weberino-right weberino-two-col" v-show="quizFinish">
                <button type="button" class="weberino-btn btn-dark" v-on:click="loadAnswers">Show Answers</button>
            </div>


            <div class="weberino-grid-item weberino-center weberino-one-col" v-show="quizLoad">
                <button type="button" class="weberino-btn weberino-btn-block weberino-btn-success" v-on:keyup.enter="submit" v-on:click="loadQuestions">Start Quiz</button>
            </div>


            <div class="weberino-grid-item weberino-center weberino-one-col" v-show="quizFinish">
                {{ message }}
            </div>

            <div class="weberino-grid-item weberino-center weberino-one-col" v-show="quizFinish">
                You scored <span class="weberino-font-weight-bold weberino-lead">
                        <span>{{ score }}</span> / 10
                      </span>
            </div>

            <div class="weberino-grid-item weberino-center weberino-one-col" v-show="quizFinish">
                Share your score with your friends!
            </div>

            <div class="weberino-grid-item weberino-center weberino-share weberino-three-col"  style="opacity:0;width: 0; height: 0;">
                <div class="fb-share-button"
                     data-href="<?php echo $current_url ?>"
                     data-layout="button_count">
                </div>
            </div>

            <div class="weberino-grid-item weberino-center weberino-share weberino-three-col" style="opacity:0;width: 0; height: 0;"  >
                <a class="twitter-share-button" v-bind:href="twitterHref" data-size="large">
                    Tweet
                </a>
            </div>

            <div class="weberino-grid-item weberino-center weberino-share weberino-three-col" style="opacity:0;width: 0; height: 0;" >
                <a href="whatsapp://send?text=Check out this great quiz. See if you can beat me! <?php echo $current_url ?>" data-action="share/whatsapp/share">
                    <img src="<?php echo plugins_url() ?>/weberino-quiz/assets/img/whatsapp.png" class="weberino-whatsapp" />
                </a>
            </div>


            <div class="weberino-grid-item weberino-center weberino-one-col" v-show="quizFinish">
                <p v-show="quizFinish">Copy link<br/>
                    <span class="border p-1"> <?php echo $current_url ?> </span>
                </p>
            </div>

            <div class="weberino-grid-item weberino-col-span-9" v-show="quizPlay">
                <input type="text" class="weberino-form-control" placeholder="Enter your answer" v-model="answer">
            </div>

            <div class="weberino-grid-item weberino-col-span-3" v-show="quizPlay">
                <input type="hidden" v-model="quizId" name="quiz_id" value="<?php echo $atts['id']; ?>">
                <button type="button" class="weberino-btn weberino-btn-primary" v-on:keyup.enter="checkAnswer" v-on:click="checkAnswer">Submit</button>
            </div>

            <div class="weberino-alert weberino-alert-success weberino-w-100 weberino-grid-item weberino-center weberino-one-col"  v-show="correctAnswer">
                    Correct Answer!
            </div>

            <div class="weberino-alert weberino-alert-danger weberino-w-100 weberino-grid-item weberino-center weberino-one-col" role="alert" v-show="wrongAnswer">
                    Wrong Answer!
            </div>

            <div class="weberino-alert weberino-alert-dark weberino-w-100 weberino-grid-item weberino-center weberino-one-col" role="alert"  v-show="emptyAnswer">
                    Enter an answer!
            </div>

            <div class="weberino-alert weberino-alert-dark weberino-w-100 weberino-grid-item weberino-center weberino-one-col" role="alert" v-show="alreadyAnswered">
                    You've already answered that!
            </div>


            <div class="weberino-grid-item weberino-col-span-2">
                rank
            </div>
            <div class="weberino-grid-item weberino-col-span-5">
                Hint
            </div>
            <div class="weberino-grid-item weberino-col-span-5">
                Answer
            </div>

            <div class="weberino-grid-container weberino-one-col" v-for="(question, key, index) in questions">
                <div class="weberino-grid-item weberino-col-span-2 weberino-left">
                    {{ question.id }}
                </div>
                <div class="weberino-grid-item weberino-col-span-5 weberino-left">
                    {{ question.question }}
                </div>
                <div class="weberino-grid-item weberino-col-span-5 weberino-left">
                    <span :ref="'answer'+key" ></span>
                </div>
            </div>

            <div class="weberino-grid-item weberino-one-col" >
                <button type="button" class="weberino-btn weberino-btn-light" v-show="quizPlay" v-on:click='closeQuiz'>Give up</button>
            </div>

    </div>

</div>