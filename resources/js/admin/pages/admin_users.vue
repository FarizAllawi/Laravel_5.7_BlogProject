<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags <Button type="success" @click="addModal=true"><Icon type="md-add"/> Add Admin</Button></p>
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<thead>
								<tr>
									<th>ID</th>
									<th>Tag Name</th>
									<th>Created at</th>
									<th>Action</th>
								</tr>,
							</thead>
								<!-- TABLE TITLE -->
								<!-- ITEMS -->
								<tr v-for="(tag , i) in tags" :key="i">
									<td>{{ tag.id }}</td>
									<td class="_table_name">{{ tag.tagName }}</td>
									<td>{{ tag.created_at }}</td>
									<td>
										<Button type="info" size="small" @click="showEditModal(tag , i)">Edit</Button>
										<Button type="error" size="small" @click="showDeletingModal(tag , i)" :loading="tag.isDeleting">Delete</Button>
									</td>
								</tr>
						</table>
					</div>

					<!-- tag adding modal -->
					<Modal
						v-model="addModal"
						title = 'Add Admin'
						:mask-closabl="false"
						:closable="false"
					>
                        <div class="space">
						    <Input type="text" v-model="data.fullName" placeholder="Full Name"/>
                        </div>
                        <div class="space">
						    <Input type="email" v-model="data.email" placeholder="Email"/><br>
                        </div>
                        <div class="space">
						    <Input type="password" v-model="data.password" placeholder="Password"/>
                        </div>
                        <div class="space">
						    <Select v-model="data.userType" placeholder="Select Admin Type">
                                <Option value='Admin'>Admin</Option>
                                <Option value='Editor'>Editor</Option>
                            </Select>
                        </div>
						
						


						<div slot="footer">
							<Button type="default" @click="addModalClose">Close</Button>
							<Button type="primary" @click="addAdmin" :disabled="isAdding" >{{ isAdding ? 'Adding..' : 'Add Users' }}</Button>

						</div>
					</Modal>

					<!-- tag edit modal -->
					<Modal
						v-model="editModal"
						title = 'Edit Tag'
						:mask-closabl="false"
						:closable="false"
					>
						
						<Input v-model="editData.tagName" placeholder="Edit Tag Name"/>

						<div slot="footer">
							<Button type="default" @click="editModal=false">Close</Button>
							<Button type="primary" @click="editTag" :disabled="isAdding" >{{ isAdding ? 'Editing..' : 'Edit Tag' }}</Button>

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
                fullName : '',
                email    : '',
                password : '',
                userType : 'Admin',
 
			},
			addModal 		:false,
			editModal		: false,
			isAdding 		: false,
			tags	 		: [],
			editData 		: {
				tagName: '',
			},
			index	 		: -1,
			showDeleteModal : false,
			isDeleting		: false,
			deleteItem 		: {},
			deletingIndex	:-1,
		}
	}, 

	methods : {
		async addAdmin() {
            if (this.data.fullName.trim() == '') return this.e('Full name is required');
			if (this.data.email.trim() == '') return this.e('Email is required');
			if (this.data.password.trim() == '') return this.e('Password is required');
			if (this.data.userType.trim() == '') return this.e('User Type is required');
            
			const res = await this.callApi('post', 'app/addUsers' , this.data);

			if (res.status === 201) {
				this.tags.unshift(res.data);
				this.s('Admin user has been added Succesfully');
				this.addModal = false;
                this.data.fullName = '';
				this.data.email = '';
				this.data.password = '';
				this.data.userType = '';   
			}
			else {
				if (res.status === 422) {
                    // Info the error message
                    for (let i in res.data.errors)
                        this.i(res.data.errors[i][0])
				}
				else {
					this.swr();
				}
			}
		},

		addModalClose() {
			this.addModal = false
			this.data.tagName  = ''
		},

		async editTag() {
			// if (this.data.tagName.trim() == '') return this.e('Tag name is required');
			const res = await this.callApi('post', 'app/editTag' , this.editData);

			if (res.status === 200) {
				this.tags[this.index].tagName = this.editData.tagName;
				this.s('Tag has been edited Succesfully');
				this.editModal = false;
			}
			else {
				if (res.status === 422) {
					if (res.data.errors.tagName[0]) 
						this.i(res.data.errors.tagName[0])
				}
				else {
					this.swr();
				}
			}
		},

		showEditModal(tag , index) {
			let obj = {
				id 	 : tag.id,
				tagName : tag.tagName
			};
			this.editData = obj;
			this.editModal = true;
			this.index = index;
		},

		showDeletingModal(tag , index) {
			const deleteModalObj  = {
				showDeleteModal : true,
				deleteUrl       : 'app/deleteTag',
				data            : tag,
				deletingIndex	: index,
				isDeleted       : false,
			}
			this.$store.commit('setDeletingModalObj', deleteModalObj)
		}
	
	},

	async created() {
		const res = await this.callApi('get','app/getTags');
		console.log(res)
		if (res.status === 200) 
			this.tags = res.data
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
			if (obj.isDeleted) {
				this.tags.splice(obj.deletingIndex)
			}
		}
	}

	
	
}
</script>