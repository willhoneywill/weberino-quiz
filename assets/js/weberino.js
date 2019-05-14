var wtimer = {
    props: [
        'mins', 'seconds'
    ],
    template: '<span>{{ mins | two_digits }}:{{ seconds | two_digits }}</span>'
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
            answers: ['sdfsdf', 'dfsdf', 'fsdfsdfsdf', 'qweqweqwe'],
            questions: [],
            correctAnswer: false,
            wrongAnswer: false,
            alreadyAnswered: false,
            emptyAnswer: false,
            score: 0,
            mins: 3,
            seconds: 0,
            timeLimit: 3000
        }
    },
    mounted () {
        console.log('mount the horse');
    },
    components: {
        'weberino-timer': wtimer
    },
    methods: {
        loadQuestions: function() {
            this.quizPlay = true;
            this.quizLoad = false;
            this.quizFinish = false;
            this.timeLimit = 3000;

            this.answers.splice(2, 0, "Lene");

            var data = new FormData();

            data.append('action', 'load_questions');
            data.append('id', 26);
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
                data.append('id', 26);
                axios
                    .post('/wp-admin/admin-ajax.php', data)
                    .then(response => (
                        this.updateResult(response)
                    ));
            }

        },
        updateResult: function( response) {
            if(response.data.correct === true) {
                this.correctAnswer = true;
                this.wrongAnswer = false;
                this.emptyAnswer = false;
                this.alreadyAnswered = false;
                this.score = this.score + 1;
            }else{
                this.correctAnswer = false;
                this.wrongAnswer = true;
                this.emptyAnswer = false;
                this.alreadyAnswered = false;
            }
            this.answer = '';
        },
        closeQuiz: function () {
            this.quizFinish = true;
        }
    }
});