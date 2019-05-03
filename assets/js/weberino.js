Vue.component('weberino-quiz', {
    props: ['message'],
    template: '<li> sdsd{{ message }} </li>'
});

new Vue({
    el: '#weberino-app',
    data () {
        return {
            message: 'hi'
        }
    },
    mounted () {
        console.log('mount the horse')
    }
});