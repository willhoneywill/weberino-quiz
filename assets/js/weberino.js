var wtimer = {
    props: [
        'mins', 'seconds'
    ],
    template: '<span>{{ mins | two_digits }}:{{ seconds | two_digits }}</span>'
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
            timeLimit: 180000,
            howToPlay: false,
            quizId: document.querySelector("input[name=quiz_id]").value,
            answered: [],
            message: ''
        }
    },
    mounted () {

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
            this.timeLimit = 180000;

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
            var x = setInterval(function() {

                self.mins = Math.floor((self.timeLimit % (1000 * 60 * 60)) / (1000 * 60));
                self.seconds = Math.floor((self.timeLimit % (1000 * 60)) / 1000);

                self.timeLimit = self.timeLimit - 1000;

                if (self.timeLimit < 0) {
                    clearInterval(x);
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
        },
        checkAnswer: function() {

            if (this.answer === '') {
                this.correctAnswer = false;
                this.wrongAnswer = false;
                this.emptyAnswer = true;
                this.alreadyAnswered = false;
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
        updateResult: function( response) {
            this.correctAnswer = false;
            this.wrongAnswer = false;
            this.emptyAnswer = false;
            this.alreadyAnswered = false;

            if(response.data.correct === true) {
                if (this.answered.includes(response.data.key[0])){
                    this.alreadyAnswered = true;
                } else {
                    for (const key of Object.keys(response.data.answer)) {
                       this.$refs['answer' + response.data.key[key]][0].innerText = response.data.answer[key];
                        this.answered.push(response.data.key);
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
            this.loadMessage();
            // load the message
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