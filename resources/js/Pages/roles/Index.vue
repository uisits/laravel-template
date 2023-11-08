<template>
    <div class="max-w-6xl mx-auto">
        <v-dialog v-model="dialog" max-width="800px">
            <v-card raised>
                <v-card-title>
                    <span class="font-semibold text-2xl">Add New Role</span>
                    <v-spacer></v-spacer>
                    <v-icon color="error" @click="dialog = false">cancel</v-icon>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mt-3">
                        <v-text-field v-model="editedItem.name" label="Role Name" outlined prepend-icon="mdi-fingerprint"></v-text-field>
                    </div>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn outlined color="error" @click="close">Cancel</v-btn>
                    <v-btn :loading="loadingUpdate" color="success" @click="onSubmit(editedItem)">Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-card raised class="shadow-lg">
            <v-card-title>
                <span class="text-3xl font-semibold">Roles</span>
                <v-spacer></v-spacer>
                <v-btn class="mx-4" @click="dialog = true" color="primary" outlined><v-icon>mdi-person_add</v-icon>Add Role</v-btn>
                <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
            </v-card-title>
            <v-data-table multi-sort :loading="loadingRoles" :headers="headers" :search="search" :items="roles" class="elevation-1">
                <template v-slot:item.name="{ item }">
                    <v-chip class="ma-2" color="success" text-color="white">{{ item.name }}</v-chip>
                </template>
                <template v-slot:item.action="{ item }">
                    <v-icon @click="deleteItem(item)" color="danger">mdi-delete</v-icon>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>
<script>
export default {
    data: () => ({
        loadingRoles: false,
        dialog: false,
        appName: process.env.MIX_APP_NAME,
        appUrl: process.env.MIX_APP_URL,
        csrfToken: document.querySelector('meta[name="csrf-token"]').content,
        search: '',
        loadingUpdate: false,
        editedIndex: -1,
        editedItem: {
            id: null,
            name: null,
        },
        defaultItem: {
            id: null,
            name: null,
        },
        headers: [
            { text: '#id', value: 'id'},
            { text: 'name', value: 'name'},
            { text: 'Created At', value: 'created_at'},
            { text: 'Updated At', value: 'updated_at'},
            { text: 'Action', value: 'action', sortable: false },
        ],
    }),
    props:{
        user:{
            type: Object,
            required: true,
        },
        roles: {
            type: Array,
            required: true
        }
    },
    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'New Role' : 'Edit Role'
        },
    },
    watch: {
        dialog (val) {
            val || this.close()
        },
    },
    created(){
    },
    methods: {
        editItem (item) {
            this.editedIndex = this.roles.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },
        close () {
            this.dialog = false;
            this.editedItem = Object.assign({}, this.defaultItem);
            this.editedIndex = -1;
        },
        deleteItem(item) {
            this.editedIndex = this.roles.indexOf(item);
            this.editedItem = Object.assign({}, item);
            const shouldDelete = confirm('Are you sure you want to delete this?');
            if(shouldDelete) {
                this.loadingUpdate = true;
                axios.post(process.env.MIX_APP_URL + '/api/role/' + item.id,{
                    name: item.name,
                    _method: 'delete'
                }).then((response) => {
                    if (response.status === 200) {
                        console.log(response)
                        this.roles.splice(this.editedIndex, 1);
                        console.log(this.roles)
                        this.snack = true;
                        this.snackColor = 'success';
                        this.snackText = 'Successfully Updated';
                    } else {
                        this.snack = true;
                        this.snackColor = 'error';
                        this.snackText = 'Oops! Something went wrong!';
                    }
                    this.close();
                }).catch(error => {
                    this.snack = true;
                    this.snackColor = 'error';
                    this.snackText = 'Oops! Something went wrong!'
                }).finally(() => {
                    this.loadingUpdate = false;
                });
            }
        },
        onSubmit(item) {
            // Add
            this.loadingUpdate = true;
            axios.post(process.env.MIX_APP_URL + '/api/role',{
                name: item.name,
            }).then((response) => {
                if (response.status === 201) {
                    console.log(response.data)
                    this.roles.push(response.data);
                    console.log(this.roles)
                    this.snack = true;
                    this.snackColor = 'success';
                    this.snackText = 'Successfully Updated';
                } else {
                    this.snack = true;
                    this.snackColor = 'error';
                    this.snackText = 'Oops! Something went wrong!';
                }
                this.close();
            }).catch(error => {
                this.snack = true;
                this.snackColor = 'error';
                this.snackText = 'Oops! Something went wrong!'
            }).finally(() => {
                this.loadingUpdate = false;
            });
        }
    }
}
</script>
