document.addEventListener('DOMContentLoaded', function () {
    console.log('✅ DOMContentLoaded - Page fully loaded.');

    // Get modal elements
    const addProductModal = document.getElementById('addProductModal');
    const uploadImageModal = document.getElementById('uploadImageModal');
    const addProductForm = document.getElementById('addProductForm');
    const uploadImageForm = document.getElementById('uploadImageForm');
    const finishUploading = document.getElementById('finishUploading');

    console.log('📦 addProductModal:', addProductModal);
    console.log('📦 uploadImageModal:', uploadImageModal);
    console.log('📦 addProductForm:', addProductForm);
    console.log('📦 uploadImageForm:', uploadImageForm);
    console.log('📦 finishUploading Button:', finishUploading);

    // Instead of capturing buttons directly, use event delegation
    document.addEventListener('click', function (event) {
        // Handle Open Add Product Modal
        if (event.target && event.target.id === 'openAddProductModal') {
            console.log('✅ openAddProductModal button clicked.');
            if (addProductModal) {
                console.log('🟢 Opening Add Product Modal...');
                addProductModal.classList.remove('hidden');
                addProductModal.classList.add('active');
            } else {
                console.error('❌ Error: addProductModal not found when trying to open.');
            }
        }

        // Handle Cancel Add Product Modal
        if (event.target && event.target.id === 'cancelAddProduct') {
            console.log('✅ cancelAddProduct button clicked.');
            if (addProductModal) {
                console.log('🔵 Closing Add Product Modal...');
                addProductModal.classList.remove('active');
                addProductModal.classList.add('hidden');
            } else {
                console.error('❌ Error: addProductModal not found when trying to close.');
            }
        }
    });
});
