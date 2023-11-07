<template>
    <div class="container">
        <div class="px-2">
            <div class="flex flex-wrap justify-between content-center -mx-2">
                <div v-for="role in roles" class="w-full md:w-1/2 px-2 mt-3">
                    <v-card raised :shaped="true">
                        <v-card-title>{{ role.role }}</v-card-title>
                        <v-card-text>
                            <p class="text-semibold">Click the button to select the {{ role.role}} role and view all pending petitions requiring your approval.</p>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn outlined color="info" :href="appUrl + role.url">{{ role.role }}</v-btn>
                        </v-card-actions>
                    </v-card>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                appUrl: process.env.MIX_APP_URL,
            }
        },
        props: {
            user:{
                required: true,
                type: Object
            },
            roles:{
                required: true,
                type: Array
            }
        },
        created() {

        }
    }
</script>
