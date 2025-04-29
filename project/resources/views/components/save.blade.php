<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle save/unsave functionality
        document.querySelectorAll('.unsave-btn').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');
                const bookmarkIcon = this.querySelector('i');
                const isSaved = bookmarkIcon.classList.contains('fas');

                fetch(isSaved ? `/unsave/${postId}` : `/save/${postId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (response.ok) {
                            // Toggle the bookmark icon
                            if (isSaved) {
                                bookmarkIcon.classList.remove('fas');
                                bookmarkIcon.classList.add('far');
                            } else {
                                bookmarkIcon.classList.remove('far');
                                bookmarkIcon.classList.add('fas');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    });
</script> 