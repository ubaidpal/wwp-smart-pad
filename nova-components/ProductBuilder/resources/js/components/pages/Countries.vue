<template>
    <span>
         <dropdown-trigger v-if="selected_country_message" class="h-9 flex items-center">
            <span class="text-90">{{ selected_country_message}}</span>
        </dropdown-trigger>
        <dropdown-trigger v-else-if="selected_country" class="h-9 flex items-center" :handle-click="toggle">
            <img v-bind:src="selected_country.flag" class="rounded-full w-8 h-8 mr-3" style="object-fit: cover;"/>
            <span class="text-90">{{ selected_country.iso2_code }}</span>
        </dropdown-trigger>
        <dropdown-trigger v-else="" class="h-9 flex items-center" :handle-click="toggle">
            <span class="text-90">{{ selected_country_msg_empty }}</span>
        </dropdown-trigger>
        <dropdown-menu slot="menu" width="120" direction="rtl" v-if="show">
        <ul class="list-reset">
            <li v-for="country in countries" class="h-9 flex items-center" @click="changeCountry(country.id,country.flag,country.iso3_code)" >
                <img v-bind:src="country.flag" class="rounded-full w-8 h-8 mr-3" style="object-fit: cover;">
                <span class="text-90 items-center">{{ country.iso2_code }}</span>
            </li>
        </ul>
        </dropdown-menu>
    </span>
</template>

<script>
    export default {
        name: "Countries",
        data: () => ({
            countries:[],
            show:false,
            currentFlag:'https://restcountries.eu/data/aus.svg',
            currentCode:'AU',
            selected_country:0,
            selected_country_msg_empty:'',
            selected_country_message:''
        }),
        methods: {
            toggle() {
                this.show = !this.show;
            },
            async changeCountry(id,flag,code){
                this.currentFlag =  flag;
                this.currentCode =  code;
                let response = await Nova.request().get('/nova-vendor/ProductBuilder/setSession/'+id);
                this.show = !this.show;
                if(response.data.selected_country.is_favourite != 0){
                    this.selected_country = response.data.selected_country;
                } else {
                    this.selected_country_message = 'Please Select At least One Active Country.';
                }

                this.$router.push('/');

            }
        },
        async created(){

            const response = await Nova.request().get('/nova-vendor/ProductBuilder/countries');
            this.countries = response.data.countries;
            if(response.data.success === true){

                if(this.countries){
                    this.selected_country = this.countries.find(x => x.selected == 1);
                    if(!this.selected_country){
                        this.selected_country = this.countries[1];
                    }

                }
            }else{
                if(this.countries.length > 0 ){
                    this.selected_country = this.countries[0];
                }else{
                    this.selected_country_msg_empty = 'Please Select At least One Active Country.';
                }

            }


        }
    }
</script>

<style scoped>

</style>
