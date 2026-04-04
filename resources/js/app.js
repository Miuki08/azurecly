import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine

document.addEventListener('alpine:init', () => {
    Alpine.data('contactPage', () => ({
        showModal: false,
        mode: 'create',
        form: {
            id: null,
            name: '',
            email: '',
            phone: '',
            position: '',
            institution: '',
            category: document
                .querySelector('[data-default-category]')?.dataset.defaultCategory || 'humas',
            favorite: false,
            notes: '',
        },

        openCreate() {
            this.mode = 'create'
            this.resetForm()
            this.showModal = true
        },

        openEdit(contact) {
            this.mode = 'edit'
            this.form.id = contact.id
            this.form.name = contact.Name
            this.form.email = contact.Email
            this.form.phone = contact.Phone
            this.form.position = contact.Position
            this.form.institution = contact.Institution
            this.form.category = contact.Category
            this.form.favorite = !!contact.Favorite
            this.form.notes = contact.Notes
            this.showModal = true
        },

        closeModal() {
            this.showModal = false
        },

        resetForm() {
            this.form = {
                id: null,
                name: '',
                email: '',
                phone: '',
                position: '',
                institution: '',
                category: document
                    .querySelector('[data-default-category]')?.dataset.defaultCategory || 'humas',
                favorite: false,
                notes: '',
            }
        },
    }))
})

Alpine.start()

