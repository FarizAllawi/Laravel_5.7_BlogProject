<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <h1>I will show how all other components react to changes</h1>
                <h2>The master components : {{ counter }}</h2>

                <br>
                <div>
                    <comA></comA>
                </div>
                <br>
                <div>
                    <comB></comB>
                </div>
                <br>
                <div>
                    <comC></comC>
                </div>

                <br>
                <Button type='info' @click="changeCounter">Change The state of the counter</Button>
            </div>
        </div>
    </div>
</template>

<script>
import comA from './comA.vue'
import comB from './comB.vue'
import comC from './comC.vue'
import {mapGetters} from 'vuex'

export default {
    data() {
        return {
            localVar : ''
        }
    },

    computed: {
        ...mapGetters({
            'counter' : 'getCounter',
        })
    }, 

    created() {
        
    },

    methods: {
        changeCounter() {
            this.$store.dispatch('changeCounterAction' , 1)
            this.$store.commit('changeTheCounter', 1)
        },
        runSomethingWhenCounterChange() {
            console.log('I am running based on each changes happening')
        }
    },

    components : {
        comA,
        comB,
        comC
    },

    watch : {
        counter(value) {
            console.log("counter is changing" ,value);
            this.runSomethingWhenCounterChange()
            console.log('Local Var', this.localVar)
        }
    }

}
</script>