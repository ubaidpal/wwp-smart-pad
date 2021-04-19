<template>
    <loading-view :loading="loading">
        <bread-crumbs :breadcrumbs="breadcrumbs"></bread-crumbs>
        <heading class="mb-3">{{__('')}} {{ singularName }}</heading>

        <card class="overflow-hidden">
            <form v-if="fields" @submit.prevent="createResource" autocomplete="off">
                <!-- Validation Errors -->
                <validation-errors :errors="validationErrors"/>

                <!-- Fields -->
                <div v-for="field in fields">
                    <component
                        :is="'form-' + field.component"
                        :errors="validationErrors"
                        :resource-name="resourceName"
                        :field="field"
                        :via-resource="viaResource"
                        :via-resource-id="viaResourceId"
                        :via-relationship="viaRelationship"
                    />
                </div>

                <!-- Create Button -->
                <div class="bg-30 flex px-8 py-4">
                    <button dusk="create-and-add-another-button" type="button" @click="createAndAddAnother" class="ml-auto btn btn-default btn-primary mr-3">
                        {{__('Save &amp; Create Another')}}
                    </button>

                    <button dusk="create-button" class="btn btn-default btn-primary">
                        {{__('Save')}} {{ singularName }}
                    </button>
                </div>
            </form>
        </card>
    </loading-view>
</template>

<script>
import { Errors, Minimum, InteractsWithResourceInformation } from 'laravel-nova'

export default {
    mixins: [InteractsWithResourceInformation],

    props: {
        resourceName: {
            type: String,
            required: true,
        },
        viaResource: {
            default: '',
        },
        viaResourceId: {
            default: '',
        },
        viaRelationship: {
            default: '',
        },
    },

    data: () => ({
        relationResponse: null,
        loading: true,
        fields: [],
        validationErrors: new Errors(),
        resourceUrl:'',
        breadcrumbs:[]
    }),

    async created() {
        // If this create is via a relation index, then let's grab the field
        // and use the label for that as the one we use for the title and buttons
        if (this.isRelation) {
            const { data } = await Nova.request(
                '/nova-api/' + this.viaResource + '/field/' + this.viaRelationship
            )
            this.relationResponse = data;

        }

        this.getFields();
        this.breadcrumbs=[
            {url:'/'+this.resourceName,label:this.resourceName},
            {url:'',label:'New '+this.singularName},
        ];
    },

    methods: {

        /**
         * Get the available fields for the resource.
         */
        async getFields() {
            this.fields = []

            const { data: fields } = await Nova.request().get(
                `/nova-api/${this.resourceName}/creation-fields`
            )

            this.fields = fields
            this.loading = false
        },

        /**
         * Create a new resource instance using the provided data.
         */
        async createResource() {
            try {
                const response = await this.createRequest()

                this.$toasted.show(
                    this.__('The :resource was created!', {
                        resource: this.resourceInformation.singularLabel.toLowerCase(),
                    }),
                    { type: 'success' }
                )

                // this.$router.push({
                //     name: 'detail',
                //     params: {
                //         resourceName: this.resourceName,
                //         resourceId: response.data.id,
                //     },
                // })
                this.$router.push({
                    path: '/'+this.resourceName,
                    params: {
                        resourceName: this.resourceName,
                    },
                })
            } catch (error) {
                if (error.response.status == 422) {
                    this.validationErrors = new Errors(error.response.data.errors)
                }
            }
        },

        /**
         * Create a new resource and reset the form
         */
        async createAndAddAnother() {
            try {
                const response = await this.createRequest()

                this.$toasted.show(
                    this.__('The :resource was created!', {
                        resource: this.resourceInformation.singularLabel.toLowerCase(),
                    }),
                    { type: 'success' }
                )

                // Reset the form by refetching the fields
                this.getFields()

                this.validationErrors = new Errors()
            } catch (error) {
                if (error.response.status == 422) {
                    this.validationErrors = new Errors(error.response.data.errors)
                }
            }
        },

        /**
         * Send a create request for this resource
         */
        createRequest() {
            return Nova.request().post(
                `/nova-api/${this.resourceName}`,
                this.createResourceFormData()
            )
        },

        /**
         * Create the form data for creating the resource.
         */
        createResourceFormData() {
            return _.tap(new FormData(), formData => {
                _.each(this.fields, field => {
                    field.fill(formData)
                })

                formData.append('viaResource', this.viaResource)
                formData.append('viaResourceId', this.viaResourceId)
                formData.append('viaRelationship', this.viaRelationship)
            })
        },
    },

    computed: {
        singularName() {
            let label = '';
            if (this.relationResponse) {
                label = this.relationResponse.singularLabel;
            }

            label = this.resourceInformation.singularLabel;

            let splittedLabels = label.split(/(?=[A-Z])/);
            return splittedLabels.join(' ');
        },

        isRelation() {
            return Boolean(this.viaResourceId && this.viaRelationship)
        },
    },
}
</script>

<style>
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
