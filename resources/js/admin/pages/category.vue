<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Category <Button type="success" @click="addModal=true"><Icon type="md-add"/> Add New Category</Button></p>
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<thead>
								<tr>
									<th>ID</th>
									<th>Icon Image</th>
									<th>Category Name</th>
									<th>Created at</th>
									<th>Action</th>
								</tr>,
							</thead>
								<!-- TABLE TITLE -->
								<!-- ITEMS -->
								<tr v-for="(category , i) in categoryList" :key="i" v-if="categoryList.length">
									<td>{{ category.id }}</td>
									<td class="table_image"><img :src="category.iconImage"/></td>
									<td>{{ category.categoryName }}</td>
									<td>{{ category.created_at }}</td>
									<td>
										<Button type="info" size="small" @click="showEditModal(category , i)">Edit</Button>
										<Button type="error" size="small" @click="showDeletingModal(category , i)" :loading="category.isDeleting">Delete</Button>
									</td>
								</tr>
						</table>
					</div>

					<!-- Category adding modal -->
					<Modal
						v-model="addModal"
						title = 'Add Category'
						:mask-closabl="false"
						:closable="false"
					>
						
						<Input v-model="data.categoryName" placeholder="Add Category Name"/>
                        <Upload
                            ref="uploads"
                            type="drag"
                            :headers = "{'x-csrf-token': token , 'X-Requested-with' : 'XMLHttpRequest'}" 
                            :on-success="handleSuccess"
                            :on-error="handleError"
                            :format="['jpg','jpeg','png']"
                            :max-size="2048"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="/app/upload">
                            <div style="padding: 20px 0">
                                <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                <p>Click or drag files here to upload</p>
                            </div>
                        </Upload>
                        <div class="demo-upload-list" v-if="data.iconImage" >
                            <img :src="`/uploads/${data.iconImage}`" />
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-trash-outline" @click="deleteImage()"></Icon>
                            </div>
                        </div>

						<div slot="footer">
							<Button type="default" @click="addModal=false">Close</Button>
							<Button type="primary" @click="addCategory" :disabled="isAdding" >{{ isAdding ? 'Adding..' : 'Add Category' }}</Button>

						</div>
					</Modal>

					<!-- tag edit modal -->
					<Modal
						v-model="editModal"
						title = 'Edit Category'
						:mask-closabl="false"
						:closable="false"
					>
						
						<Input v-model="editData.categoryName" placeholder="Edit Category Name"/>
						<Upload v-show="isIconImageNew"
                            ref="editDataUploads"
                            type="drag"
                            :headers = "{'x-csrf-token': token , 'X-Requested-with' : 'XMLHttpRequest'}" 
                            :on-success="handleSuccess"
                            :on-error="handleError"
                            :format="['jpg','jpeg','png']"
                            :max-size="2048"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="/app/upload">
                            <div style="padding: 20px 0">
                                <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                                <p>Click or drag files here to upload</p>
                            </div>
                        </Upload>
                        <div class="demo-upload-list" v-if="editData.iconImage" >
                            <img :src="editData.iconImage" />
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
                            </div>
                        </div>

						<div slot="footer">
							<Button type="default" @click="closeEditModal">Close</Button>
							<Button type="primary" @click="editCategory" :disabled="isAdding" >{{ isAdding ? 'Editing..' : 'Edit Category' }}</Button>

						</div>
					</Modal>

					<!-- tag delete modal -->
					<Modal
						v-model="showDeleteModal"
						width  = "360"
						title = 'Edit Tag'
						:mask-closabl="false"
						:closable="false"
					>
						<p slot="header" style="color:#f60; text-align:center">
							<Icon type="ios-information-circle"></Icon>
							<span>Delete Confirmation</span>
						</p>
						
						<div style="text-align:center">
							<p>Are you sure you want to delete tag ?</p>
						</div>
						<div slot="footer">
							<Button type="error"  size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteTag">Delete Tag</Button>
						</div>
					</Modal>
 
				</div>

			</div>
		</div>
    </div>
</template>

<script>
export default {
	data() {
		return {
			data : {
                categoryName : '',
                iconImage    : '',
 
			},
			addModal 		 :false,
			editModal		 : false,
			isAdding 		 : false,
			categoryList     : [],
			editData 		 : {
				categoryName : '',
				iconImage	 : ''
			},
			index	 		 : -1,
			showDeleteModal  : false,
			isDeleting		 : false,
			deleteItem 		 : {},
            deletingIndex	 :-1,
			token            : '',
			isIconImageNew	 : false,
			isEditingItems	 : false,
		}
	}, 

	methods : {
		async addCategory() {
            if (this.data.categoryName.trim() == '') return this.e('Category name is required');
            if (this.data.iconImage.trim() == '') return this.e('Icon Image is required');
			const res = await this.callApi('post', 'app/addCategory' , this.data);

			if (res.status === 201) {
				this.categoryList.unshift(res.data);
				this.s('Category has been added Succesfully');
				this.$refs.uploads.clearFiles();
				this.addModal = false;
				this.data.categoryName = '';
				this.data.iconImage = '';
			}
			else {
				if (res.status === 422) {
					if (res.data.errors.categoryName[0])
                        this.i(res.data.errors.categoryName[0])

                    if (res.data.errors.iconImage[0])
						this.i(res.data.errors.iconImage[0])
				}
				else {
					this.swr();
				}
			}
		},

		async editCategory() {
			if (this.editData.categoryName.trim() == '') return this.e('Category name is required');
            if (this.editData.iconImage.trim() == '') return this.e('Icon Image is required');
			const res = await this.callApi('post', 'app/editCategory' , this.editData);

			if (res.status === 200) {
				this.categoryList[this.index].categoryName = this.editData.categoryName;
				this.categoryList[this.index].iconImage = this.editData.iconImage;
				this.s('Category has been edited Succesfully');
				this.editModal = false;
			}
			else {
				if (res.status === 422) {
					if (res.editData.errors.categoryName[0])
                        this.i(res.editData.errors.categoryName[0])

                    if (res.editData.errors.iconImage[0])
						this.i(res.editData.errors.iconImage[0])
				}
				else {
					this.swr();
				}
			}
		},

		showEditModal(category , index) {
			let obj = {
				id 	 		 : category.id,
				categoryName : category.categoryName,
				iconImage	 : category.iconImage
			};
			this.editData = obj;
			this.editModal = true;
			this.index = index;
			this.isEditingItems = true;
		},

		async deleteTag(tag , index) {
			this.isDeleting = true;
			const res = await this.callApi('post' , 'app/deleteTag', this.deleteItem);

			if (res.status===200) {
				this.tags.splice(this.deletingIndex , 1);
				this.s('Tag has been delleted succesfully !');
			}
			else 
				this.swr()
			this.isDeleting = false;
			this.showDeleteModal = false;
		},

		showDeletingModal(tag , index) {
			this.deleteItem      = tag;
			this.deletingIndex	 = index;
			this.showDeleteModal = true;
        },
        
        handleView (name) {
            this.imgName = name;
            this.visible = true;
        },
        handleRemove (file) {
            const fileList = this.$refs.upload.fileList;
            this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
        },
        handleSuccess (res, file) { 
			if (this.isEditingItems) {
				res = `/uploads/${res}`;
				return this.editData.iconImage = res;
			}
            this.data.iconImage = res;
        },
        handleError (res, file) { 
            // Notice Something Went Wrong
            this.swr(file.errors.file[0]);
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: file.name + ' is incorrect, please select jpg or png.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: file.name+' is too large, no more than 2M.'
            });
        },

        async deleteImage(isAdd=true) {
			// For editing
			let image ;
			if (!isAdd) {
				this.isIconImageNew = true;
				image = this.editData.iconImage;
				this.editData.iconImage = '';
				this.$refs.editDataUploads.clearFiles();
			}
			else {
				image = '/uploads/'+this.data.iconImage;
				this.data.iconImage = '';
				this.$refs.uploads.clearFiles();
			}

            const res = await this.callApi('post', 'app/deleteUpload', {imageName: image});
            if (res.status != 200) {
                this.data.iconImage = image
                this.swr();
            }
		},
		closeEditModal() {
			this.isEditingItems = false;
			this.editModal = false;
		}


	
	},

	async created() {
        this.token = window.Laravel.csrf_token;
		const res = await this.callApi('get','app/getCategory');
		console.log(res)
		if (res.status === 200) 
			this.categoryList = res.data
		else 
			this.swr()
    },
    
}
</script>
<style>
    .table_image  img{
        width: 100px;
        height: 50px;
    }
    .demo-upload-list{
        display: inline-block;
        text-align: center;
        margin:0;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,.2);
        margin-left: 11%;
    }
    .demo-upload-list img{
        width: 384px;
        height: 216px;
    }
    .demo-upload-list-cover{
        display: none;
        position: absolute;
		width:100%;
		height: 100%;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .demo-upload-list:hover .demo-upload-list-cover{
        display: block;
    }
    .demo-upload-list-cover i{
        color: #fff;
		width:100%;
		height: 100%;
        font-size: 50px;
        cursor: pointer;
        margin-top: 20%;
    }
</style>