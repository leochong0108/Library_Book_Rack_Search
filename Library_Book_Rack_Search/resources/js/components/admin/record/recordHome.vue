<template>
    <div class="d-flex align-items-center justify-content-between mb-5">
        <h1>Record Page</h1>
    </div>

    <div v-if="loading" class="text-center" style="padding: 13rem;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div v-if="!records.length">
        <p>No Record available.</p>
    </div>

    <div v-if="records.length">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Record ID</th>
                    <th scope="col">Book id</th>
                    <th scope="col">Remark</th>
                    <th scope="col">Action</th>
                    <th scope="col">Expired Date</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="record in records" :key="record.id">
                    <td>{{ record.id }}</td>
                    <td>{{ record.book_id }}</td>
                    <td>{{ record.remark }}</td>
                    <td>{{ record.action }}</td>
                    <td>{{ record.expired_at }}</td>
                    <td>
                        <button class="btn btn-success" @click="editRecord(record)"><i class="fas fa-edit"></i>&nbsp;Edit</button>
                        <button class="btn btn-danger ms-2" @click="deleteRecord(record.id)"><i class="fas fa-trash-alt"></i>&nbsp;Delete</button>
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
        const records = ref([]);
        const error = ref(null);

        const getRecord = async () => {
            try {
                const response = await axios.get('/api/getAllRecord');
                records.value = response.data.data;
                console.log(response.data.data);
            } catch (err) {
                error.value = err.response?.data?.message || 'Error fetching records';
            }
        };


        const editRecord = async (record) => {
            const { value: formValues } = await Swal.fire({
                title: 'Edit Record',
                html:
                    `<input id="remark" class="swal2-input" placeholder="Remark" value="${record.remark}">` ,
                focusConfirm: false,
                preConfirm: () => {
                    return {
                        remark: document.getElementById('remark').value,
                    };
                }
            });

            if (formValues) {
                try {
                    await axios.put(`/api/updateRecord/${record.id}`, formValues);
                    await getRecord();
                    Swal.fire('Updated!', 'Record details have been updated.', 'success');
                } catch (err) {
                    Swal.fire('Error', err.response?.data?.message || 'Could not update Record', 'error');
                }
            }
        };

        const deleteRecord = async (id) => {
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
                    await axios.delete(`/api/deleteRecord/${id}`);
                    await getRecord();
                    Swal.fire('Deleted!', 'Record has been deleted.', 'success');
                } catch (err) {
                    Swal.fire('Error', err.response?.data?.message || 'Could not delete Record', 'error');
                }
            }
        };

        onMounted(() => {
            getRecord();
        });

        return {
            records,
            error,
            getRecord,
            editRecord,
            deleteRecord,
        };
    }
};
</script>
