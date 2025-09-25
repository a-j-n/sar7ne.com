// Image Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Profile image modal
    const profileImage = document.querySelector('[data-profile-image]');
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const closeModal = document.getElementById('closeModal');

    if (profileImage && imageModal) {
        profileImage.addEventListener('click', function() {
            modalImage.src = this.src;
            modalImage.alt = this.alt;
            imageModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });

        // Close modal handlers
        const closeModalHandler = () => {
            imageModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

        closeModal?.addEventListener('click', closeModalHandler);
        
        imageModal.addEventListener('click', function(e) {
            if (e.target === imageModal) {
                closeModalHandler();
            }
        });

        // ESC key to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !imageModal.classList.contains('hidden')) {
                closeModalHandler();
            }
        });
    }

    // Message image preview
    const imageInput = document.getElementById('messageImage');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageBtn = document.getElementById('removeImage');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `
                        <div class="relative">
                            <img src="${e.target.result}" alt="Preview" class="max-w-full h-32 object-cover rounded-lg">
                            <button type="button" id="removeImagePreview" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600">×</button>
                        </div>
                    `;
                    
                    // Add remove functionality to the preview
                    document.getElementById('removeImagePreview').addEventListener('click', function() {
                        imageInput.value = '';
                        imagePreview.innerHTML = '';
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
