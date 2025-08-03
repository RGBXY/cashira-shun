const formatDate = (date) => {
    if (!date) return "N/A";

    const options = {
        day: "2-digit", // Tanggal
        month: "2-digit", // Bulan
        year: "numeric", // Tahun
        hour: "2-digit", // Jam
        minute: "2-digit", // Menit
        hourCycle: "h23", // Format 24-jam
    };

    return new Intl.DateTimeFormat("en-US", options).format(new Date(date));
};

export default formatDate;
