<template>
    <div class="d-flex align-items-center justify-content-between mb-5">
        <h1>Book Rack Page</h1>

        <button @click="createRack" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add New
        </button>
    </div>

    <div v-if="loading" class="text-center" style="padding: 13rem;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div v-if="!racks.length">
        <p>No Book Rack available. Please add some.</p>
    </div>

    <div v-if="racks.length">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Rack_ID</th>
                    <th scope="col">Rack Layer</th>
                    <th scope="col">Floor</th>
                    <th scope="col">Start</th>
                    <th scope="col">End</th><th scope="col">Start</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="rack in racks" :key="rack.id">
                    <td>{{ rack.id }}</td>
                    <td>{{ rack.rack_layer }}</td>
                    <td>{{ rack.floor }}</td>
                    <td>{{ rack.start }}</td>
                    <td>{{ rack.end }}</td>
                    <td>
                        <button class="btn btn-success" @click="editRack(rack)"><i class="fas fa-edit"></i>&nbsp;Edit</button>
                        <button class="btn btn-danger ms-2" @click="deleteRack(rack.id)"><i class="fas fa-trash-alt"></i>&nbsp;Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    setup() {
        const racks = ref([]);
        const error = ref(null);

        const getRack = async () => {
            try {
                const response = await axios.get('/api/getAllRack');
                racks.value = response.data.data;
            } catch (err) {
                error.value = err.response?.data?.message || 'Error fetching racks';
            }
        };

        const createRack = async () => {
            const { value: formValues } = await Swal.fire({
                title: 'Create New Rack',
                html:
                    '<input id="rackLayer" class="swal2-input" placeholder="Rack Layer">' +
                    '<input id="floor" class="swal2-input" placeholder="Floor">' +
                    '<input id="start" class="swal2-input" placeholder="Start">' +
                    '<input id="end" class="swal2-input" placeholder="End">',
                focusConfirm: false,
                preConfirm: () => {
                    return {
                        rack_layer: document.getElementById('rackLayer').value,
                        floor: document.getElementById('floor').value,
                        start: document.getElementById('start').value,
                        end: document.getElementById('end').value,
                    };
                }
            });

            if (formValues) {
                try {
                    await axios.post('/api/createRack', formValues);
                    await getRack();
                    Swal.fire('Created!', 'New rack has been created.', 'success');
                } catch (err) {
                    Swal.fire('Error', err.response?.data?.message || 'Could not create rack', 'error');
                }
            }
        };

        const editRack = async (rack) => {
            const { value: formValues } = await Swal.fire({
                title: 'Edit Rack',
                html:
                    `<input id="rackLayer" class="swal2-input" placeholder="Rack Layer" value="${rack.rack_layer}">` +
                    `<input id="floor" class="swal2-input" placeholder="Floor" value="${rack.floor}">` +
                    `<input id="start" class="swal2-input" placeholder="Start" value="${rack.start}">` +
                    `<input id="end" class="swal2-input" placeholder="End" value="${rack.end}">`,
                focusConfirm: false,
                preConfirm: () => {
                    return {
                        rack_layer: document.getElementById('rackLayer').value,
                        start: document.getElementById('start').value,
                        end: document.getElementById('end').value,
                    };
                }
            });

            if (formValues) {
                try {
                    await axios.put(`/api/updateRack/${rack.id}`, formValues);
                    await getRack();
                    Swal.fire('Updated!', 'Rack details have been updated.', 'success');
                } catch (err) {
                    Swal.fire('Error', err.response?.data?.message || 'Could not update rack', 'error');
                }
            }
        };

        const deleteRack = async (id) => {
            const result = await Swal.fire({
                title: 'Warning!',
                text: 'Do you want to continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
            });

            if (result.isConfirmed) {
                try {
                    await axios.delete(`/api/deleteRack/${id}`);
                    await getRack();
                    Swal.fire('Deleted!', 'Rack has been deleted.', 'success');
                } catch (err) {
                    Swal.fire('Error', err.response?.data?.message || 'Could not delete rack', 'error');
                }
            }
        };

        onMounted(() => {
            getRack();
        });

        return {
            racks,
            error,
            getRack,
            createRack,
            editRack,
            deleteRack,
        };
    }
};
</script>
