<template>
 <div>
     <multiselect :options="dealerStatus"
                  v-model="status"
                  track-by="id"
                  label="label"
                  placeholder="Select Status"
                  :option-height="40"
                  :searchable="false"
                  :allow-empty="false"
                  @input="setDealerStatus()"
     >

         <template slot="singleLabel" slot-scope="props">
             <span contenteditable="false" v-html="props.option.icon"></span> &nbsp;
             <span class="option__title">{{ props.option.label }}</span>
         </template>
         <template slot="option" slot-scope="props">
             <span contenteditable="false" v-html="props.option.icon"></span>
             <span class="option__title">{{ props.option.label }}</span>
         </template>

     </multiselect>
 </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';


  export default {
        name: "dealer-status",
        props:[
            'dealerStatus',
            'dealer',
            'status'
        ],
        data: () => ({

        }),
        components: {
            Multiselect
        },
        methods: {
            async setDealerStatus(){
                let formData = new FormData();
                formData.append('dealerId', this.dealer.id);
                formData.append('statusId', this.status.id);
                await Nova.request().post(`/nova-vendor/ProductBuilder/saveDealerStatus`,formData).then((response) => {
                    if(response.data.success){
                        this.$toasted.show(
                            this.__('The :status are Updated!',{
                                resource: 'Dealer',
                            }),
                            {type: 'success'}
                        );
                    }


                });

            },
        },
      created(){


      },


    }
</script>

<style scoped>

</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
