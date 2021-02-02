<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role User Management <Button type="success" @click="addModal=true"><Icon type="md-add"/> Add New Role</Button></p>
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<thead>
								<tr>
									<th>ID</th>
									<th>Role Type</th>
									<th>Created At</th>
									<th>Action</th>
								</tr>,
							</thead>
								<!-- TABLE TITLE -->
								<!-- ITEMS -->
								<tr v-for="(role , i) in roles" :key="i">
									<td>{{ role.id }}</td>
									<td class="_table_name">{{ role.roleName }}</td>
									<td>{{ role.created_at }}</td>
									<td>
										<Button type="info" size="small" @click="showEditModal(role , i)">Edit</Button>
										<Button type="error" size="small" @click="showDeletingModal(role , i)" :loading="role.isDeleting">Delete</Button>
									</td>
								</tr>
						</table>
					</div>

					<!-- tag adding modal -->
					<Modal
						v-model="addModal"
						title = 'Add Role'
						:mask-closabl="false"
						:closable="false"
					>
						
						<Input v-model="data.roleName" placeholder="Add Role Name"/>

						<div slot="footer">
							<Button type="default" @click="addModalClose">Close</Button>
							<Button type="primary" @click="addRole" :disabled="isAdding" >{{ isAdding ? 'Adding..' : 'Add Role' }}</Button>

						</div>
					</Modal>

					<!-- tag edit modal -->
					<Modal
						v-model="editModal"
						title = 'Edit Role'
						:mask-closabl="false"
						:closable="false"
					>
						
						<Input v-model="editData.roleName" placeholder="Edit Role Name"/>

						<div slot="footer">
							<Button type="default" @click="editModal=false">Close</Button>
							<Button type="primary" @click="editRole" :disabled="isEditing" >{{ isEditing ? 'Editing..' : 'Edit Role' }}</Button>

						</div>
					</Modal>

					<!-- tag delete modal -->
					<delete-modal></delete-modal>
				</div>

			</div>
		</div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import deleteModal from '../components/deleteModal.vue';
export default {
	data() {
		return {
			data : {
				roleName : '',
 
			},
			addModal 		:false,
			editModal		: false,
			isAdding 		: false,
			roles	 		: [],
			editData 		: {},
			index	 		: -1,
			showDeleteModal : false,
            isDeleting		: false,
			isEditing		: false,
			deletingIndex	:-1,
		}
	}, 

	methods : {
		async addRole() {
			// if (this.data.tagName.trim() == '') return this.e('Tag name is required');
			const res = await this.callApi('post', 'app/addRoles' , this.data);
            this.isAdding = true;
			if (res.status === 201) {
				this.roles.unshift(res.data);
				this.s('Role has been added Succesfully');
                this.addModal = false;
                this.isAdding = false;
				this.data.roleName = '';
			}
			else {
				if (res.status === 422) {
					if (res.data.errors.roleName[0])
						this.i(res.data.errors.roleName[0])
				}
				else {
					this.swr();
				}
            }
		},

		addModalClose() {
			this.addModal = false
			this.data.roleName  = ''
		},

		async editRole() {
			// if (this.data.tagName.trim() == '') return this.e('Tag name is required');
			const res = await this.callApi('post', 'app/editRole' , this.editData);
            this.isEditing = true;
			if (res.status === 200) {
                this.roles[this.index].roleName = this.editData.roleName;
                this.isEditing = false;
				this.s('Role has been edited Succesfully');
                this.editModal = false;
			}
			else {
				if (res.status === 422) {
					if (res.data.errors.roleName[0])
						this.i(res.data.errors.roleName[0])
				}
				else {
					this.swr();
                }
                this.isEditing = false;
            }
		},

		showEditModal(role , index) {
			let obj = {
                id       : role.id,
				roleName : role.roleName
			};
			this.editData = obj;
			this.editModal = true;
			this.index = index;
		},

		showDeletingModal(role , index) {
			const deleteModalObj  = {
				showDeleteModal : true,
				deleteUrl       : 'app/deleteRole',
				data            : role,
				deletingIndex	: index,
				isDeleted       : false,
			}
            this.$store.commit('setDeletingModalObj', deleteModalObj)
            this.deletingIndex = index
		}
	
	},

	async created() {
		const res = await this.callApi('get','app/getRoles');
		if (res.status === 200) 
			this.roles = res.data
		else 
			this.swr()
	}
	,
	components : {
		'delete-modal' : deleteModal,
	},
	computed : {
		...mapGetters(['getDeleteModalObj'])
	},
	watch : {
		getDeleteModalObj(obj) {
            let rolesData = []
			if (obj.isDeleted) {
                this.roles.splice(this.deletingIndex, 1)
			}
		}
	}

	
	
}
</script>