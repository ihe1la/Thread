import React, { useState, useEffect } from 'react';
import { getSecurityToggles, updateSecurityToggles } from '../services/api';

function AdminPanel() {
  const [toggles, setToggles] = useState({
    sanitization_level: 'strict',
    allow_svg_upload: false,
    allow_external_fetch: false,
    csp_enabled: true,
    require_strict_redirect_match: true
  });
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  useEffect(() => {
    const fetchToggles = async () => {
      try {
        const response = await getSecurityToggles();
        setToggles(response.data);
      } catch (err) {
        setError('Error fetching security toggles');
      }
    };

    fetchToggles();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await updateSecurityToggles(toggles);
      setSuccess('Security toggles updated successfully');
      setError('');
    } catch (err) {
      setError('Error updating security toggles');
      setSuccess('');
    }
  };

  return (
    <div className="card">
      <div className="card-body">
        <h3 className="card-title">Admin Security Panel</h3>
        {error && <div className="alert alert-danger">{error}</div>}
        {success && <div className="alert alert-success">{success}</div>}
        <form onSubmit={handleSubmit}>
          <div className="mb-3">
            <label className="form-label">Sanitization Level:</label>
            <select
              className="form-select"
              value={toggles.sanitization_level}
              onChange={(e) => setToggles({...toggles, sanitization_level: e.target.value})}
            >
              <option value="none">None</option>
              <option value="basic">Basic</option>
              <option value="strict">Strict</option>
            </select>
          </div>
          
          <div className="mb-3 form-check">
            <input
              type="checkbox"
              className="form-check-input"
              checked={toggles.allow_svg_upload}
              onChange={(e) => setToggles({...toggles, allow_svg_upload: e.target.checked})}
            />
            <label className="form-check-label">Allow SVG Upload</label>
          </div>

          <div className="mb-3 form-check">
            <input
              type="checkbox"
              className="form-check-input"
              checked={toggles.allow_external_fetch}
              onChange={(e) => setToggles({...toggles, allow_external_fetch: e.target.checked})}
            />
            <label className="form-check-label">Allow External Fetch</label>
          </div>

          <div className="mb-3 form-check">
            <input
              type="checkbox"
              className="form-check-input"
              checked={toggles.csp_enabled}
              onChange={(e) => setToggles({...toggles, csp_enabled: e.target.checked})}
            />
            <label className="form-check-label">Enable Content Security Policy</label>
          </div>

          <div className="mb-3 form-check">
            <input
              type="checkbox"
              className="form-check-input"
              checked={toggles.require_strict_redirect_match}
              onChange={(e) => setToggles({...toggles, require_strict_redirect_match: e.target.checked})}
            />
            <label className="form-check-label">Require Strict Redirect Match</label>
          </div>

          <button type="submit" className="btn btn-primary">Update Security Settings</button>
        </form>
      </div>
    </div>
  );
}

export default AdminPanel;