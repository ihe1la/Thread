import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { createThread } from '../services/api';

function CreateThread() {
  const [title, setTitle] = useState('');
  const [content, setContent] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await createThread(title, content);
      navigate('/threads');
    } catch (err) {
      setError('Error creating thread');
    }
  };

  return (
    <div className="card">
      <div className="card-body">
        <h3 className="card-title">Create Thread</h3>
        {error && <div className="alert alert-danger">{error}</div>}
        <form onSubmit={handleSubmit}>
          <div className="mb-3">
            <label className="form-label">Title:</label>
            <input
              type="text"
              className="form-control"
              value={title}
              onChange={(e) => setTitle(e.target.value)}
              required
            />
          </div>
          <div className="mb-3">
            <label className="form-label">Content:</label>
            <textarea
              className="form-control"
              value={content}
              onChange={(e) => setContent(e.target.value)}
              rows="5"
              required
            />
          </div>
          <button type="submit" className="btn btn-primary">Create Thread</button>
        </form>
      </div>
    </div>
  );
}

export default CreateThread;