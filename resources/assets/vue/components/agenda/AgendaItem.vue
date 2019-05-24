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
                        <a class="btn btn-outline-primary" :href="makeApplicationFormUrl()">Schrijf je in</a>
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
            'agenda'
        ],
        methods: {
            makeApplicationFormUrl(){
                return "/forms/" + this.agenda.id;
            },
            makeAgendaDetailUrl(){
                return '/agenda/' + this.agenda.id
            }
        }
    }
</script>

<style scoped>

</style>