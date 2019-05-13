Vue.component('weberino-quiz', {
    props: ['question'],
    template: '<li> sdsd{{ question }} </li>'
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
            correctAnswer: false
        }
    },
    mounted () {
        console.log('mount the horse');
    },
    methods: {
        loadQuestions: function() {
            this.quizPlay = true;
            this.quizLoad = false;

            var data = new FormData();

            data.append('action', 'load_questions');
            data.append('id', 26);
            axios
                .post('/wp-admin/admin-ajax.php', data)
                .then(response => (
                    console.log(response),
                        this.questions = response.data
                ));
        },
        checkAnswer: function() {
            var data = new FormData();

            data.append('action', 'check_answer');
            data.append('answer', this.answer);
            data.append('id', 26);
            axios
                .post('/wp-admin/admin-ajax.php', data)
                .then(response => (
                    this.updateResult( response)
                ));
        },
        updateResult: function( response) {
            console.log(response);
            console.log('sdfs');
        }
    },
    watch: {
        correctAnswer: function (value) {

            /*for (const key of Object.keys(this.newProducts)) {

                this.products.push({
                    title: this.newProducts[key].title,
                    invalidUri: this.newProducts[key].invalidUri
                });
            }

            if (this.continueQuery) {
                this.testUris();
            } else {
                this.loading = false;
            }*/
        }
    }
});