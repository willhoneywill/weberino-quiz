var wtimer = {
    props: [
        'mins', 'seconds'
    ],
    template: '<span class="weberino-bold weberino-lead">{{ mins | two_digits }}:{{ seconds | two_digits }}</span>'
};

var wanswer = {
    props: [
        'answer'
    ],
    template: '<span>{{ answer  }}</span>'
};

Vue.filter('two_digits', function (value) {
    if(value.toString().length <= 1)
    {
        return "0"+value.toString();
    }
    return value.toString();
});

new Vue({
    el: '#weberino-app',
    data () {
        return {
            message: 'dfdf',
            quizLoad: true,
            quizPlay: false,
            quizFinish: false,
            answer: '',
            questions: [],
            correctAnswer: false,
            wrongAnswer: false,
            alreadyAnswered: false,
            emptyAnswer: false,
            score: 0,
            mins: 3,
            seconds: 0,
            timeLimit: 180000, //180000 6000
            howToPlay: false,
            quizId: document.querySelector("input[name=quiz_id]").value,
            answered: [],
            message: '',
            twitterHref: 'https://twitter.com/intent/tweet?text=Check out this great quiz. See if you can beat me',
            timer: null,
            shareStyles: {
                opacity: 0,
                width: 0,
                height: 0
            }
        }
    },
    mounted: function mounted() {
        var app = this;
        window.addEventListener('keyup', function(event) {
            if (event.keyCode === 13) {
                app.checkAnswer();
            }
        });
    },
    components: {
        'weberino-timer': wtimer,
        'weberino-answer': wanswer
    },
    methods: {
        loadQuestions: function() {
            console.log(this.quizId);

            this.quizPlay = true;
            this.quizLoad = false;
            this.quizFinish = false;
            this.timeLimit = 180000; //180000 6000

            var data = new FormData();

            data.append('action', 'load_questions');
            data.append('id', this.quizId);
            axios
                .post('/wp-admin/admin-ajax.php', data)
                .then(response => (
                    console.log(response),
                        this.questions = response.data
                ));

            var self = this;
            this.timer = setInterval(function() {

                self.mins = Math.floor((self.timeLimit % (1000 * 60 * 60)) / (1000 * 60));
                self.seconds = Math.floor((self.timeLimit % (1000 * 60)) / 1000);

                self.timeLimit = self.timeLimit - 1000;

                if (self.timeLimit < 0) {
                    clearInterval(self.timer);
                    console.log('time finished');
                    self.closeQuiz();
                }
            }, 1000);
        },
        loadAnswers: function() {
            var data = new FormData();

            data.append('action', 'load_answers');
            data.append('id', this.quizId);
            axios
                .post('/wp-admin/admin-ajax.php', data)
                .then(response => (
                    this.populateAnswers(response.data)
                ));

        },
        populateAnswers: function(answers) {
            for (const key of Object.keys(answers)) {
                console.log(answers[key].answer);
                this.$refs['answer' + answers[key].id][0].innerText = answers[key].answer;
            }
        },
        playAgain: function() {
            var i = 1;
            while (i < 11) {
                this.$refs['answer' + i][0].innerText = '';
                i++;
            }
            this.loadQuestions();
            this.answer = '';
            this.score = 0;
            this.shareStyles = {
                opacity: 0,
                width: 0,
                height: 0
            }
            this.answered = [];
        },
        checkAnswer: function() {

            if (this.answer === '') {
                this.hideAlertBoxes();
                this.emptyAnswer = true;
            } else {
                var data = new FormData();

                data.append('action', 'check_answer');
                data.append('answer', this.answer);
                data.append('id', this.quizId);
                axios
                    .post('/wp-admin/admin-ajax.php', data)
                    .then(response => (
                        this.updateResult(response)
                    ));
            }

        },
        hideAlertBoxes: function() {
            this.correctAnswer = false;
            this.wrongAnswer = false;
            this.emptyAnswer = false;
            this.alreadyAnswered = false;
        },
        updateResult: function( response) {
            this.hideAlertBoxes();

            if(response.data.correct === true) {
                if (this.answered.includes(response.data.key[0])){
                    this.alreadyAnswered = true;
                } else {
                    for (const key of Object.keys(response.data.answer)) {
                       this.$refs['answer' + response.data.key[key]][0].innerText = response.data.answer[key];
                        this.answered.push(response.data.key[key]);
                        this.score = this.score + 1;
                        this.correctAnswer = true;
                    }
                }
            }else{
                this.wrongAnswer = true;
            }
            this.answer = '';
        },
        closeQuiz: function () {
            this.quizFinish = true;
            this.quizPlay = false;
            this.shareStyles = {
                opacity: 100,
                width: '100%',
                height: '100%'
            };
            this.loadMessage();
            this.hideAlertBoxes();
            clearInterval(this.timer);
        },
        loadMessage: function () {
            var data = new FormData();

            data.append('action', 'load_message');
            data.append('score', this.score);
            data.append('id', this.quizId);
            axios
                .post('/wp-admin/admin-ajax.php', data)
                .then(response => (
                    this.message = response.data.message
                ));
        }
    },
    watch: {
        score: function () {
            if (this.score == 10) {
                this.closeQuiz();
            }
        }
    }
});

window.twttr = (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0],
        t = window.twttr || {};
    if (d.getElementById(id)) return t;
    js = d.createElement(s);
    js.id = id;
    js.src = "https://platform.twitter.com/widgets.js";
    fjs.parentNode.insertBefore(js, fjs);

    t._e = [];
    t.ready = function(f) {
        t._e.push(f);
    };

    return t;
}(document, "script", "twitter-wjs"));

(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));