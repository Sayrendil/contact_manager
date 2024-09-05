<template>
  <div>
    <h2>Contacts</h2>

    <!-- Search and Filter -->
    <el-form :model="filters" label-width="120px" class="filter-form">
      <el-form-item label="Search">
        <el-input v-model="filters.search" placeholder="Search by name" @input="applyFilters" />
      </el-form-item>
      <el-form-item label="Content">
        <el-input v-model="filters.content" placeholder="Search by content" @input="applyFilters" />
      </el-form-item>
      <el-form-item label="Author Name">
        <el-input v-model="filters.authorName" placeholder="Search by author name" @input="applyFilters" />
      </el-form-item>
      <el-form-item label="Created After">
        <el-date-picker
          v-model="filters.createdAfter"
          type="date"
          placeholder="Select date"
          @change="applyFilters"
        />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="applyFilters">Apply Filters</el-button>
      </el-form-item>
    </el-form>

    <el-form @submit.prevent="addContact" :model="newContact" label-width="120px">
      <el-form-item label="Name">
        <el-input v-model="newContact.name" placeholder="Name" />
      </el-form-item>
      <el-form-item label="Content">
        <el-input v-model="newContact.content" type="textarea" rows="4" placeholder="Content" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="addContact">Add Contact</el-button>
      </el-form-item>
    </el-form>

    <el-table :data="filteredContacts" style="width: 100%" stripe>
      <el-table-column prop="name" label="Name" />
      <el-table-column prop="content" label="Content" />
      <el-table-column label="Author Name">
        <template v-slot="scope">
          {{ scope.row.author ? scope.row.author.name : 'Unknown' }}
        </template>
      </el-table-column>
      <el-table-column label="Created At">
        <template v-slot="scope">
          {{ new Date(scope.row.created_at).toLocaleDateString() }}
        </template>
      </el-table-column>
      <el-table-column label="Actions">
        <template #default="scope">
          <el-button @click="openViewDialog(scope.row)" type="info" size="small">View</el-button>
          <el-button @click="openEditDialog(scope.row)" type="warning" size="small">Edit</el-button>
          <el-button @click="deleteContact(scope.row.id)" type="danger" size="small">Delete</el-button>
        </template>
      </el-table-column>
    </el-table>

    <!-- Edit Contact Modal -->
    <el-dialog
      title="Edit Contact"
      v-model="editDialogVisible"
      width="30%"
      @close="resetEditForm"
    >
      <el-form :model="editContact" label-width="120px">
        <el-form-item label="Name">
          <el-input v-model="editContact.name" placeholder="Name" />
        </el-form-item>
        <el-form-item label="Content">
          <el-input v-model="editContact.content" type="textarea" rows="4" placeholder="Content" />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="updateContact">Update</el-button>
          <el-button @click="editDialogVisible = false">Cancel</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>

    <!-- View Contact Modal -->
    <el-dialog
      title="View Contact"
      v-model="viewDialogVisible"
      width="30%"
      @close="resetViewForm"
    >
      <div>
        <p><strong>Name:</strong> {{ viewContact.name }}</p>
        <p><strong>Content:</strong> {{ viewContact.content }}</p>
        <p><strong>Author:</strong> {{ viewContact.author ? viewContact.author.name : 'Unknown' }}</p>
        <p><strong>Created At:</strong> {{ new Date(viewContact.created_at).toLocaleDateString() }}</p>
      </div>
      <el-button @click="viewDialogVisible = false">Close</el-button>
    </el-dialog>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      contacts: [],
      newContact: {
        name: '',
        content: '',
      },
      filters: {
        search: '',
        content: '',
        authorName: '',
        createdAfter: null,
      },
      editDialogVisible: false,
      editContact: {},
      viewDialogVisible: false,
      viewContact: {},
    };
  },
  computed: {
    filteredContacts() {
      const searchQuery = this.filters.search.toLowerCase();
      const contentQuery = this.filters.content.toLowerCase();
      const authorNameQuery = this.filters.authorName.toLowerCase();
      const createdAfter = this.filters.createdAfter ? new Date(this.filters.createdAfter) : null;

      return this.contacts.filter(contact => {
        const nameMatches = contact.name.toLowerCase().includes(searchQuery);
        const contentMatches = contact.content.toLowerCase().includes(contentQuery);
        const authorMatches = contact.author ? contact.author.name.toLowerCase().includes(authorNameQuery) : false;
        const createdAfterMatches = createdAfter ? new Date(contact.created_at) >= createdAfter : true;

        return nameMatches && contentMatches && authorMatches && createdAfterMatches;
      });
    },
  },
  async created() {
    await this.fetchContacts();
  },
  methods: {
    async fetchContacts() {
      try {
        const response = await axios.get('/api/contacts', {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        });
        this.contacts = response.data;
      } catch (error) {
        console.error('Error fetching contacts:', error);
      }
    },
    async addContact() {
      try {
        const userId = localStorage.getItem('user_id'); // Get user ID from localStorage
        await axios.post('/api/contacts', {
          name: this.newContact.name,
          content: this.newContact.content,
          author_id: userId, // Pass user ID
        }, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        });
        this.newContact.name = '';
        this.newContact.content = '';
        await this.fetchContacts();
      } catch (error) {
        console.error('Error adding contact:', error);
      }
    },
    async deleteContact(id) {
      try {
        await axios.delete(`/api/contacts/${id}`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        });
        await this.fetchContacts();
      } catch (error) {
        console.error('Error deleting contact:', error);
      }
    },
    openEditDialog(contact) {
      this.editContact = { ...contact }; // Make a copy to avoid direct mutation
      this.editDialogVisible = true;
    },
    async updateContact() {
      try {
        await axios.put(`/api/contacts/${this.editContact.id}`, this.editContact, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        });
        this.editDialogVisible = false;
        await this.fetchContacts();
      } catch (error) {
        console.error('Error updating contact:', error);
      }
    },
    openViewDialog(contact) {
      this.viewContact = { ...contact }; // Make a copy to avoid direct mutation
      this.viewDialogVisible = true;
    },
    resetEditForm() {
      this.editContact = {};
    },
    resetViewForm() {
      this.viewContact = {};
    },
    applyFilters() {
      // Trigger computed property update
    },
  },
};
</script>

<style scoped>
.el-form {
  margin-bottom: 20px;
}
.el-form-item {
  margin-bottom: 15px;
}
.el-table {
  margin-top: 20px;
}
.filter-form {
  margin-bottom: 20px;
}
</style>
