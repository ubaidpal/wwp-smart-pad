<template>
    <div class="main">
        <div class="size_heading"><span class="size_input">{{ size_name}} Sub Option</span>
        </div>
        <div class="size_heading_child">
            <label>Min size value:</label>
            <input type="number" @input="triggerEvent" name="min_value"
                                                  class="w-full form-control form-input form-input-bordered"
                                                  v-model="sub_options.min_value" id="min_value" ><br/>
            <label>Max size value:</label>
            <input type="number" @input="triggerEvent" name="max_value"
                                                  class="w-full form-control form-input form-input-bordered"
                                                  v-model="sub_options.max_value" id="max_value" value="">
        </div>
    </div>
</template>

<script>
    export default {
        name: "OptionSize",
        props: ['option', 'sub_options' ,'size_name'],
        async created() {
            //console.log(typeof sub_options);
            console.log(this.option);
        },
        methods: {
            triggerEvent(event) {
                console.log('>>>>>>'+event.target.value);
                this.$emit('input', this.option, event.target.name, event.target.value);
            }
        }
    }
</script>
<style scoped>
    
    .size_heading{
        background: #f6f6f6;
        font-weight: 800;
        color: var(--80);
        text-transform: uppercase;
        border-bottom-width: 2px;
        border-color: var(--50);
        letter-spacing: .05em;
        vertical-align: middle;
        text-align: center;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .size_heading_child{
        border: 10px #525558;
        padding: 15px;
        background: #f6f6f6;
        background-clip: border-box;
        text-align: center;
    }
    .size_heading_child:hover{
        border: 10px #525558;
        padding: 15px;
        background-color: #f1ebeb;
        background-clip: border-box;
        text-align: center;
    }
    .size_input{
        font-weight: 800;
        font-size: .75rem;
        color: var(--80);
        text-transform: uppercase;
        border-bottom-width: 2px;
        border-color: var(--50);
        letter-spacing: .05em;
        vertical-align: middle;
        text-align: center;
        border: none;
    }
</style>
