Vue.component('weberino-quiz', {
    props: ['question'],
    template: '<li> sdsd{{ question }} </li>'
});

new Vue({
    el: '#weberino-app',
    data () {
        return {
            message: 'dfdf',
            questions: [
                { question: 'Foo' },
                { question: 'Bar' }
            ]
        }
    },
    mounted () {
        var data = new FormData();

        data.append('action', 'load_questions');
        data.append('id', 26);
        axios
            .post('/wp/wp-admin/admin-ajax.php', data)
            .then(response => (
                this.questions = response.data.questions
            ));
    }
});