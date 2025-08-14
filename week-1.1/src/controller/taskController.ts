import { PrismaClient } from '@prisma/client';
const prisma = new PrismaClient();

exports.addTask = async (req, res) => {
    const {name,status,userId} = req.body;
    try {
        const newTask = await prisma.task.create({
            data: {
                name,
                status,
                userId
            }
        });
        res.status(201).json({ message: "Task created successfully", task: newTask });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: "Internal server error" });
    }
}

exports.getTasks = async (req, res) => {
    const { userId } = req.params;
    try {
        const tasks = await prisma.task.findMany({
            where: { userId }
        });
        res.status(200).json(tasks);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: "Internal server error" });
    }
}

exports.updateTask = async (req, res) => {
    const { id } = req.params;
    const { name, status } = req.body;
    try {
        const updatedTask = await prisma.task.update({
            where: { id },
            data: { name, status }
        });
        res.status(200).json({ message: "Task updated successfully", task: updatedTask });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: "Internal server error" });
    }
}

exports.deleteTask = async (req, res) => {
    const { id } = req.params;
    try {
        await prisma.task.delete({
            where: { id }
        });
        res.status(200).json({ message: "Task deleted successfully" });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: "Internal server error" });
    }
}