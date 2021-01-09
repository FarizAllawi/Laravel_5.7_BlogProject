<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags <Button type="success" @click="addModal=true"><Icon type="md-add"/> Add New Tag</Button></p>
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Tag Name</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							
							<tr v-for="(tag , i) in tags" :key="i" v-if="tags.length">
								<td>{{ tag.id }}</td>
								<td class="_table_name">{{ tag.tagName }}</td>
								<td>{{ tag.Created_at }}</td>
								<td>
									<Button type="info" size="small">Info</Button>
									<Button type="error" size="small">Error</Button>
								</td>
							</tr>
						</table>
					</div>

					<!-- tag adding modal -->
					<Modal
						v-model="addModal"
						title = 'Add Tag'
						:mask-closabl="false"
						:closable="false"
					>
						
						<Input v-model="data.tagName" placeholder="Add Tag Name"/>

						<div slot="footer">
							<Button type="default" @click="addModal=false">Close</Button>
							<Button type="primary" @click="addTag" :disabled="isAdding" >{{ isAdding ? 'Adding..' : 'Add Tag' }}</Button>

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
				tagName : '',
 
			},
			addModal :false,
			isAdding : false,
			tags: [],
		}
	}, 

	methods : {
		async addTag() {
			if (this.data.tagName.trim() == '') return this.e('Tag name is required');
			const res = await this.callApi('post', 'app/createTag' , this.data);

			if (res.status === 201) {
				this.tags.unshift(res.data);
				this.s('Tag has been added Succesfully');
				this.addModal = false;
				this.data.tagName = '';
			}
			else 
				this.swr();
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
}
</script>