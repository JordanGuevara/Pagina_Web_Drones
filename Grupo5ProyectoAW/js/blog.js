document.getElementById('load-comments').addEventListener('click', async function() {
    try {
        const response = await fetch('https://randomuser.me/api/?results=10');
        const data = await response.json();

        const commentsContainer = document.getElementById('comments-container');
        const averageRatingContainer = document.getElementById('average-rating');
        commentsContainer.innerHTML = ''; 

        let totalRating = 0;
        const numberOfComments = data.results.length;

        data.results.forEach(user => {
            const rating = Math.floor(Math.random() * 5) + 1;
            totalRating += rating;

            const commentElement = document.createElement('div');
            commentElement.classList.add('comment');

            commentElement.innerHTML = `
                <img src="${user.picture.medium}" alt="User Picture">
                <div class="rating">${rating} ★</div>
                <p><strong>${user.name.first} ${user.name.last}</strong></p>
                <p>${user.email}</p>
                <p>${user.location.city}, ${user.location.country}</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
            `;

            commentsContainer.appendChild(commentElement);
        });

        const averageRating = (totalRating / numberOfComments).toFixed(1);
        averageRatingContainer.textContent = `${averageRating} ★`;

    } catch (error) {
        console.error('Error fetching comments:', error);
    }
});
