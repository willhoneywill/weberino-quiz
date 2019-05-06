<div id="weberino-app">
    <li>
        <weberino-quiz v-for="question in questions" v-bind:product="question" v-bind:key="question.id" >  </weberino-quiz>
    </li>

    <ul id="example-1">
        <li v-for="question in questions">
            {{ question.question }}
        </li>
    </ul>
</div>