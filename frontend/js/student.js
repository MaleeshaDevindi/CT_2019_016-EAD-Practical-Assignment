async function fetchEnrolledCourses() {
    try {
        const response = await fetch('../../api/students/my-courses.php', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        });
        if (!response.ok) throw new Error('Failed to fetch courses');
        const courses = await response.json();
        const coursesList = document.getElementById('courses-list');
        coursesList.innerHTML = '';
        if (courses.length === 0) {
            coursesList.innerHTML = '<p>No courses enrolled.</p>';
            return;
        }
        courses.forEach(course => {
            coursesList.innerHTML += `
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">${course.title}</h5>
                            <p class="card-text">${course.description.substring(0, 100)}...</p>
                            <a href="course-details.php?id=${course.id}" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            `;
        });
    } catch (error) {
        console.error('Error fetching courses:', error);
        document.getElementById('courses-list').innerHTML = '<p class="text-danger">Error loading courses.</p>';
    }
}

async function fetchCourseDetails(courseId) {
    try {
        const response = await fetch(`../../api/students/course-details.php?id=${courseId}`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        });
        if (!response.ok) throw new Error('Failed to fetch course details');
        const course = await response.json();
        document.getElementById('course-title').textContent = course.title;
        document.getElementById('course-description').textContent = course.description;
        document.getElementById('course-instructor').textContent = course.instructor_name;
    } catch (error) {
        console.error('Error fetching course details:', error);
        document.getElementById('course-details').innerHTML = '<p class="text-danger">Error loading course details.</p>';
    }
}

async function fetchProfile() {
    try {
        const response = await fetch('../../api/students/profile.php', {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        });
        if (!response.ok) throw new Error('Failed to fetch profile');
        const profile = await response.json();
        document.getElementById('username').value = profile.username;
        document.getElementById('email').value = profile.email;
        document.getElementById('name').value = profile.name;
    } catch (error) {
        console.error('Error fetching profile:', error);
        alert('Error loading profile.');
    }
}

async function updateProfile(event) {
    event.preventDefault();
    const email = document.getElementById('email').value;
    const name = document.getElementById('name').value;
    try {
        const response = await fetch('../../api/students/profile.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, name })
        });
        if (!response.ok) throw new Error('Failed to update profile');
        alert('Profile updated successfully!');
    } catch (error) {
        console.error('Error updating profile:', error);
        alert('Error updating profile.');
    }
}