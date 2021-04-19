<template>
 <div>
     <ul class="breadcrumb">
         <router-link tag="li" to="/">
             <a>Dashboard</a>
         </router-link>

         <template v-for="crumb in breadcrumbs">

             <router-link tag="li" :to="crumb.url" v-if="crumb.url != ''">
                 <a>{{ capitalizeString(crumb.label) }}</a>
             </router-link>
             <li v-else>{{ capitalizeString(crumb.label) }} </li>
         </template>
     </ul>
 </div>
</template>

<script>


  export default {
        name: "bread-crumbs",
        data: () => ({
        }),
        props:['breadcrumbs'],
        components: {

        },
        methods: {
            capitalizeString(text=''){
                return text.charAt(0).toUpperCase() + text.slice(1);
            },
        }

    }
</script>

<style scoped>

    ul.breadcrumb {
        list-style: none;
        background-color: #eee;
        float:right;
    }
    ul.breadcrumb li {
        display: inline;
        font-size: 15px;
    }
    ul.breadcrumb li+li:before {
        padding: 8px;
        color: black;
        content: "/\00a0";
    }
    ul.breadcrumb li a {
        color: #0275d8;
        text-decoration: none;
    }
    ul.breadcrumb li a:hover {
        color: #01447e;
        text-decoration: underline;
    }
</style>
