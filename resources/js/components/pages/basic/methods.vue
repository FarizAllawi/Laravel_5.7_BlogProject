<template>
    <div>
        <h1>the number is : {{ views }}</h1>
        <button @click="updateCounter(1)">Increase</button>
        <button @click="updateCounter(-1)">Decrease</button>

        <div v-for="(blog , i) in blogs" :key="i">
            <h1>{{ blog.title }}</h1> 
            <p>{{ blog.post }}</p>
        </div>

        <br><br><br>

        <div>
            <h1 v-if="showItem">Show this item if "showItem" is true</h1>
            <h1 v-else>Show this item if "showItem" is false</h1>
        </div>

        <button @click="showItem = !showItem">Change show[item]</button>
        
    </div>
</template>
<script>
export default {
    data() {
        return {
            views : 0,
            blogs : [],
            showItem : false,
        }
    },

    methods : {
        updateCounter(number) {
            this.views += number
        },


    },

    created() {
        // Call the server to get views of this blog post...
        this.views = 100

        // Get the blog posts
        let posts = [
            {
                title :  'this is blog title 1' ,
                post  : 'this is the blog post',
                id    : 1,
            },
            {
                title :  'this is blog title 2' ,
                post  : 'this is the blog post',
                id    : 2,
            },
            {
                title :  'this is blog title 3' ,
                post  : 'this is the blog post',
                id    : 3,
            },
            {
                title :  'this is blog title 4' ,
                post  : 'this is the blog post',
                id    : 4,
            },
        ]
        this.blogs = posts
    }
}
</script>