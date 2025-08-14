import express from 'express';
const router = express.Router();
const taskController = require('../controller/taskController');
const authMiddleware = require('../middleware/authMiddleware');


router.post('/', authMiddleware, taskController.addTask);
router.get('/', authMiddleware, taskController.getTasks);
router.put('/:id', authMiddleware, taskController.updateTask);
router.delete('/tasks/:id', authMiddleware, taskController.deleteTask);

module.exports = router;