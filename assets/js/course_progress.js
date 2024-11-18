document.addEventListener('DOMContentLoaded', function() {
    const accessButtons = document.querySelectorAll('.access-content');
    const quizButtons = document.querySelectorAll('.take-quiz');
    const progressBar = document.querySelector('.progress-bar');

    function updateProgress(itemType, itemId, chapterId) {
        const courseId = new URLSearchParams(window.location.search).get('course_id');

        fetch('update_progress.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `item_type=${itemType}&item_id=${itemId}&chapter_id=${chapterId}&course_id=${courseId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update progress bar
                progressBar.style.width = `${data.progress}%`;
                progressBar.setAttribute('aria-valuenow', data.progress);
                progressBar.textContent = `${data.progress}%`;

                // Update button or badge
                const listItem = document.querySelector(`[data-${itemType}-id="${itemId}"]`).closest('li');
                if (listItem.querySelector('a')) {
                    listItem.querySelector('a').remove();
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-success';
                    badge.textContent = 'Completed';
                    listItem.appendChild(badge);
                }
            } else {
                console.error('Failed to update progress');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    accessButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const subChapterId = this.dataset.subChapterId;
            const chapterId = this.dataset.chapterId;
            updateProgress('sub_chapter', subChapterId, chapterId);
            window.location.href = this.href;
        });
    });

    quizButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const quizId = this.dataset.quizId;
            const chapterId = this.dataset.chapterId;
            updateProgress('quiz', quizId, chapterId);
            window.location.href = this.href;
        });
    });
});