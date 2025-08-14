import express from "express";
const router = express.Router();
const authController  = require("../controller/authController");
const userController = require("../controller/userController");
const authMiddleware = require("../middleware/authMiddleware");


router.post("/register", authController.register);
router.post("/login", authController.login);

router.get("/profile", authMiddleware, userController.getProfile);
router.put("/profile", authMiddleware, userController.updateProfile);
router.delete("/profile", authMiddleware, userController.deleteProfile);

module.exports = router;