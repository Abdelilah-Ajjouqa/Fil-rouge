<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all save buttons
        const saveButtons = document.querySelectorAll('.save-btn');

        // Add click event listener to each button
        saveButtons.forEach(button => {
            button.addEventListener('click', async function() {
                const postId = this.getAttribute('data-post-id');
                const isSaved = this.getAttribute('data-saved') === 'true';
                const bookmarkIcon = this.querySelector('.bookmark-icon');
                const bookmarkText = this.querySelector('.bookmark-text');

                try {
                    // Set up CSRF token for Axios
                    const token = document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content');
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

                    // Make the request using async/await
                    const response = await axios.post(
                        isSaved ? `/unsave/${postId}` : `/save/${postId}`, {}, {
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        }
                    );

                    // If the request was successful
                    if (response.status === 200) {
                        // Toggle the saved state
                        const newSavedState = !isSaved;

                        // Update the button attributes and appearance
                        this.setAttribute('data-saved', newSavedState ? 'true' : 'false');

                        // Toggle the bookmark icon class
                        if (newSavedState) {
                            bookmarkIcon.classList.remove('far');
                            bookmarkIcon.classList.add('fas');
                            bookmarkText.textContent = 'Saved';
                        } else {
                            bookmarkIcon.classList.remove('fas');
                            bookmarkIcon.classList.add('far');
                            bookmarkText.textContent = 'Save';
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>
