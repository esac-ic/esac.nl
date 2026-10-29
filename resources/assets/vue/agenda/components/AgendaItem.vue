<template>
    <div class="col-lg-4 col-md-6 d-flex flex-wrap">
        <div class="card w-100 position-relative">
            <a :href="makeAgendaDetailUrl()">
                <img class="card-img-top" :src="agenda.thumbnail" alt="Card image cap">
            </a>
            <span class="card-date position-absolute bg-light py-1 px-3 rounded">{{agenda.startDate}}</span>
            <div class="card-body">
                <a :href="makeAgendaDetailUrl()">
                    <h4 class="card-title">{{agenda.title}}</h4>
                    <p class="card-text text-body">{{agenda.text}}</p>
                </a>
            </div>
            <div v-if="agenda.formId" class="card-footer bg-white p-3">
                <div class="row justify-content-between align-items-center">
                    <div class="col-auto text-muted">
                        <span class="ion-person-stalker"></span> {{agenda.amountOfPeopleRegisterd}}
                    </div>
                    <div v-show="agenda.canRegister" class="col-auto">
                        <form v-if="checkIfCurrentUserSignedUp()" method="POST" :action="makeApplicationFormUrl()">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" :value="csrfToken">
                            <button type="submit" class="btn btn-outline-danger">{{unregisterText}}</button>
                        </form>
                        <a v-if="!checkIfCurrentUserSignedUp()" class="btn btn-outline-primary" :href="makeApplicationFormUrl()">{{registerText}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
        name: "AgendaItem",
        props: [
            'agenda',
        ],
        methods: {
            makeApplicationFormUrl(){
                return "/forms/" + this.agenda.id;
            },
            makeAgendaDetailUrl(){
                return '/agenda/' + this.agenda.id
            },
            checkIfCurrentUserSignedUp() {
                return this.agenda.currentUserSignedUp;
            }
        },
        data() {
            return {
                unregisterText: 'Unregister',
                registerText: 'Register now',
                csrfToken: window.Laravel.csrfToken,
            }
        },
        mounted() {
            this.unregisterText = 'Unregister'
            this.registerText = 'Register now'

        }
    }
</script>

<style scoped>

</style>