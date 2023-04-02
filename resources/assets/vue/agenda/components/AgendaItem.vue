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
                        <a v-if="checkIfCurrentUserSignedUp()" class="btn btn-outline-primary" style="color:red" :href="makeAgendaSignOffUrl()">{{deregesterText}}</a>
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
            'lang'
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
                deregesterText: 'Afmelden',
                regesterText: 'Schrijf je in'
            }
        },
        mounted() {
            /**
             * I can't figure out how to make the language of the agenda item bits update in time because as far as I can tell, when the agenda items are loaded
             * the current language preference is not loaded yet so it defaults to nl.
             * If there was a way to make sure the current language is already updated when the agenda items are fetched by AgendaController.php it would automatically translate.
             */
            
            //set the language for the stuff that doesn't come from agenda items
            if (this.lang == 'en') {
                this.deregesterText = 'Unregister'
                this.regesterText = 'Register now'
            } else if (this.lang == 'nl') { 
                this.deregesterText = 'Afmelden'
                this.regesterText = 'Schrijf je in'
            } else {
                //currently the default language is dutch
                this.deregesterText = 'Afmelden'
                this.regesterText = 'Schrijf je in'
            }
            
            
        }
    }
</script>

<style scoped>

</style>