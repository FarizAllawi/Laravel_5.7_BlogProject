<template>
    <div>
        <Modal
            :value="getDeleteModalObj.showDeleteModal"
            :mask-closable="false"
            :closable="false"
            width  = "360"
        >
            <p slot="header" style="color:#f60; text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>Delete Confirmation</span>
            </p>
            
            <div style="text-align:center">
                <p>Are you sure you want to delete this item ?</p>
            </div>
            <div slot="footer">
                <Button type="default"  size="large"  @click="closeModal">Close</Button>
                <Button type="error"  size="large"  :loading="isDeleting" :disabled="isDeleting" @click="deleteModal">Delete</Button>
            </div>
        </Modal>
    </div>
</template>
<script>
import {mapGetters} from 'vuex'
export default {
    data() {
        return {
            isDeleting : false,
        }
    },

    methods : {
        async deleteModal() {
            this.isDeleting = true
			const res = await this.callApi('post' , this.getDeleteModalObj.deleteUrl , this.getDeleteModalObj.data);

			if (res.status===200) {
                this.s('Item has been delleted succesfully !');
                this.$store.commit('setDeleteModal' , true)
                this.isDeleting = false
			}
            else {
				this.swr()
                this.$store.commit('setDeleteModal' , false)
            }
        },
        closeModal() {
            this.$store.commit('setDeleteModal', false)
        }
    },

    computed : {
        ...mapGetters(['getDeleteModalObj'])

    },
}
</script>