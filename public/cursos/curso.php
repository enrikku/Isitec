<?php
require_once __DIR__ . '\..\..\utils/utils.php';

$courseId = isset($_GET['id']) ? $_GET['id'] : null;

if ($courseId == null) {
    header("Location: ../home.php");
}
else{
    $course = obtenerCurso($courseId);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<section class="w-full py-12 md:py-24 lg:py-32">
    <div class="container px-4 md:px-6">
        <div class="flex flex-col gap-4 md:gap-8 lg:gap-12">
            <div class="space-y-2">
                <h1 class="text-3xl font-bold tracking-tighter sm:text-5xl">
                <?php echo htmlspecialchars($course['title']); ?>
        </h1>
        <p class="max-w-[700px] text-gray-500 md:text-xl/relaxed lg:text-base/relaxed xl:text-xl/relaxed dark:text-gray-400">
            <?php echo htmlspecialchars($course['description']); ?>
        </p>
        </div>
        <div class="grid gap-4">
            <h3 class="text-xl font-bold">Course Objectives</h3>
            <ul class="grid gap-2 list-disc list-inside">
                <li>
                Learn the fundamentals of JavaScript, including variables,
                data types, and operators.
                </li>
                <li>
                Understand how to use functions and control structures to
                create logic in your programs.
                </li>
                <li>
                Explore the Document Object Model (DOM) and learn how to
                manipulate HTML and CSS with JavaScript.
                </li>
                <li>
                Master advanced topics such as asynchronous programming with
                Promises and working with APIs.
                </li>
            </ul>
        </div>
        <div class="grid items-center gap-4 md:grid-cols-2 lg:gap-8">
            <div class="flex items-center space-x-4">
                <span class="relative flex shrink-0 overflow-hidden rounded-full w-12 h-12">
                    <img src="<?php echo htmlspecialchars($course['coverURL']); ?>" width="48" height="48" class="rounded-full object-cover" alt="Instructor" style="aspect-ratio: 48 / 48; object-fit: cover;">
                </span>
                <div class="space-y-1">
                    <h4 class="text-lg font-medium">Dr. Susan Johnson</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Dr. Susan Johnson is an experienced software developer with
                        over 10 years of experience. She is passionate about teaching
                        and has helped hundreds of students learn JavaScript.
                    </p>
            </div>
        </div>
        <div class="grid gap-4">
            <h3 class="text-xl font-bold">Course Topics</h3>
            <ul class="grid gap-2 list-disc list-inside md:grid-cols-2">
                <li>Introduction to JavaScript</li>
                <li>Variables and Data Types</li>
                <li>Operators and Expressions</li>
                <li>Control Structures</li>
                <li>Functions</li>
                <li>Arrays and Objects</li>
                <li>DOM Manipulation</li>
                <li>Events and Event Handling</li>
                <li>Asynchronous JavaScript</li>
                <li>Working with APIs</li>
            </ul>
        </div>
        </div>
        <div class="grid gap-4 max-w-sm mx-auto sm:max-w-none sm:grid-cols-2">
        <div class="space-y-2">
            <h3 class="text-xl font-bold">Enroll in the Course</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Fill out the form below to enroll in the Mastering JavaScript
                course. Our team will contact you with further instructions.
            </p>
        </div>
        <div class="space-y-4">
            <div class="space-y-2">
                <label class="font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm" for="name">
                    Full Name
                </label>
                <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" id="name" placeholder="Enter your full name">
            </div>
            <div class="space-y-2">
                <label class="font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-sm" for="email">
                    Email
                </label>
                <input class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" id="email" placeholder="Enter your email" type="email">
            </div>
            <button class="inline-flex items-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 px-4 py-2 w-full h-10 justify-center sm:w-auto">
                Enroll Now
            </button>
        </div>
        </div>
        <div class="grid gap-4 max-w-sm mx-auto sm:max-w-none sm:grid-cols-2">
        <div class="space-y-2">
            <h3 class="text-xl font-bold">Testimonials</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Don't just take our word for it. Here's what our students have
                to say about the Mastering JavaScript course.
            </p>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="flex items-start gap-4">
                <img src="/placeholder.svg" width="80" height="80" alt="Student" class="rounded-full overflow-hidden object-cover object-center" style="aspect-ratio: 80 / 80; object-fit: cover;">
                <div class="space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        “The course was well-structured and easy to follow.
                        The instructor provided clear explanations, and I
                        appreciated the hands-on projects that helped me apply what
                        I learned. I feel much more confident in my JavaScript
                        skills after taking this course.”
                    </p>
                    <div class="font-medium not-italic">
                    — Sarah Thompson
                    </div>
                </div>
            </div>
            <div class="flex items-start gap-4">
                <img src="/placeholder.svg" width="80" height="80" alt="Student" class="rounded-full overflow-hidden object-cover object-center" style="aspect-ratio: 80 / 80; object-fit: cover;">
                <div class="space-y-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        “As someone with no prior programming experience, I
                        was a bit intimidated by JavaScript. However, this course
                        made the concepts easy to understand. The videos were
                        engaging, and the quizzes helped reinforce my
                        understanding.”
                    </p>
                    <div class="font-medium not-italic">
                    — Mark Johnson
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</section>
</body>
</html>
