# 🧠 Simple Task Manager API (TypeScript + Express + Prisma)

## 📌 Description

This project is a secure and scalable **RESTful API** built with:

* **Node.js + Express.js** for server logic
* **TypeScript** for safer, cleaner code
* **Prisma ORM** for database interaction
* **JWT** for authentication
* **bcrypt** for password hashing

It provides **user registration/login**, **task management**, and **user profile control** — all protected by JWT middleware.
---

## ⚙️ Project link 👇

[github](https://github.com/Fitsumhelina/taskify.git)


---

## 💻 Running the App (Development Mode)

You can run it after building using `npm run build `;

```bash
node dist/app.js
```
---

## 🔐 Auth & User Endpoints

**Base Path:** `/api/users`

| Method | Endpoint    | Protected | Description                 |
| ------ | ----------- | --------- | --------------------------- |
| POST   | `/register` | ❌         | Register new user           |
| POST   | `/login`    | ❌         | Login and receive JWT token |
| GET    | `/profile`  | ✅         | Get current user profile    |
| PUT    | `/profile`  | ✅         | Update user name/email      |
| DELETE | `/profile`  | ✅         | Delete user and their data  |

### 🔐 JWT Usage

All protected routes require the following header:

```
Authorization: Bearer <your_token_here>
```

---

## 📋 Task Endpoints

**Base Path:** `/api/tasks`

| Method | Endpoint     | Protected | Description                |
| ------ | ------------ | --------- | -------------------------- |
| POST   | `/`          | ✅         | Create a new task          |
| GET    | `/`          | ✅         | Get all tasks for the user |
| PUT    | `/:id`       | ✅         | Update a task              |
| DELETE | `/tasks/:id` | ✅         | Delete a task              |


## 🔍 Error Handling

* 400 for validation errors
* 401 for auth errors
* 500 for internal server issues (with `console.error`)

---

## ✨ Improvements You Could Add

* ✅ Zod or Joi for request validation
* ✅ Swagger documentation
* ✅ Role-based access control
* ✅ Pagination for task fetching
* ✅ Unit tests with Jest or Vitest

---

## 🧠 Author

Built by [**Fitsum**](https://www.linkedin.com/in/fitsum-helina-57164828a), a passionate software engineer based in Ethiopia 🇪🇹.

