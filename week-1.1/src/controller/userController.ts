
const { PrismaClient } = require("@prisma/client");
const prisma = new PrismaClient();

exports.getProfile = async (req, res) => {
    try {
        const userId = req.user.userId;
        const user = await prisma.user.findUnique({
            where: { id: userId },
            select: { name: true, email: true },
        });
        if (!user) {
            return res.status(404).json({ message: "User not found" });
        }
        res.status(200).json(user);
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: "Internal server error" });
    }
}
exports.updateProfile = async (req, res) => {
    const { name, email } = req.body;
    try {
        const userId = req.user.userId;
        const updatedUser = await prisma.user.update({
            where: { id: userId },
            data: { name, email },
        });
        res.status(200).json({ message: "Profile updated successfully", user: updatedUser });
    } catch (error) {
        console.error(error);
        res.status(500).json({ message: "Internal server error" });
    }
}

exports.deleteProfile = async (req, res) => {
    const userId = req.user.userId;

    try{
        await prisma.user.delete({
            where : { id: userId },
        });
        res.status(200).json({ message: "Profile deleted successfully" });
    }
    catch (error) {
        console.error(error);
        res.status(500).json({ message: "Internal server error" });
    }
}