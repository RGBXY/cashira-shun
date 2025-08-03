import { ElMessage } from "element-plus";

export const notification = (type, massage) => {
    if (type === "success") {
        ElMessage({
            message: massage,
            type: "success",
        });
        return;
    }

    if (type === "error") {
        ElMessage.error(massage);
        return;
    }

    if (type === "info") {
        ElMessage(massage);
        return;
    }
};
