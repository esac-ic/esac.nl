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
                        <a v-if="checkIfCurrentUserSignedUp()" class="btn btn-outline-danger" :href="makeAgendaSignOffUrl()">{{deregesterText}}</a>
                        <a v-if="!checkIfCurrentUserSignedUp()" class="btn btn-outline-primary" :href="makeApplicationFormUrl()">{{regesterText}}</a>
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
            makeAgendaSignOffUrl(){
                return '/forms/' + this.agenda.id + "/unregister/0" //0 is added to redirect to agenda page and not the agenda item
            },
            checkIfCurrentUserSignedUp() {
                return this.agenda.currentUserSignedUp;
            }
        },
        data() {
            return {
                deregesterText: 'Unregister',
                regesterText: 'Register now'
            }
        },
        mounted() {
            this.deregesterText = 'Unregister'
            this.regesterText = 'Register now'

        }
    }
</script>

<style scoped>

</style>