<template>
    <div>
        <h1 ref="myref">HOOK TUTORIAL</h1>
        <p>My name is: {{name}}</p>
    </div>
</template>

<script>
export default {
    // BeforeCreate akan dieksekusi sebelum data() ada
    beforeCreate() {
        console.log(this.name)
    },

    data() {
        return {
            name: 'Fariz'
        }
    },

    // Created() akan dieksekusi setelah data() ada
    created() { 
        // Call mostly initial data from it
        console.log('data and other function is available but not html', this.name)
    },

    mounted() {
        console.log(this.$refs.myref)
    }
}
</script>