import { useEffect, useMemo, useState } from "react";
import api from "../services/api";

function getDoctorsCount(payload) {
    if (!payload) return 0;

    if (Array.isArray(payload)) {
        return payload.length;
    }

    if (Array.isArray(payload?.data)) {
        return payload.data.length;
    }

    if (typeof payload?.total === "number") {
        return payload.total;
    }

    return 0;
}

export default function DoctorsJsonPanel() {
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");
    const [payload, setPayload] = useState(null);

    useEffect(() => {
        let isMounted = true;

        const fetchDoctors = async () => {
            try {
                const response = await api.get("/api/doctors");
                if (!isMounted) return;
                setPayload(getDoctorsCount(response.data));
            } catch (err) {
                if (!isMounted) return;
                setError(err?.message || "Gagal mengambil data dari backend.");
            } finally {
                if (!isMounted) return;
                setLoading(false);
            }
        };

        fetchDoctors();

        return () => {
            isMounted = false;
        };
    }, []);

    const doctorsCount = useMemo(() => getDoctorsCount(payload), [payload]);

    return (
        <div className="card h-100 border-0 shadow-sm p-3 custom-card font-iosevka">
            <div className="d-flex align-items-center justify-content-between">
                <div>
                    <p className="text-muted small mb-1">Total Dokter (API)</p>
                    <p className="h2 fw-bold text-dark mb-0">
                        {loading ? "..." : error ? "-" : doctorsCount}
                    </p>
                </div>
                <div className="bg-primary p-2 rounded-2 text-white">
                    <i className="bi bi-person fs-4"></i>
                </div>
            </div>

            <hr />

            <p className="text-muted small mb-2">Response JSON dari Laravel:</p>

            {loading && <p className="mb-0">Mengambil data... dari {api.defaults.baseURL}</p>}

            {error && (
                <div className="alert alert-danger py-2 px-3 mb-0" role="alert">
                    {error}
                </div>
            )}

            {!loading && !error && (
                <pre className="bg-light border rounded p-2 mb-0 small" style={{ maxHeight: "220px", overflow: "auto" }}>
                    {JSON.stringify(payload, null, 2)}
                </pre>
            )}
        </div>
    );
}
