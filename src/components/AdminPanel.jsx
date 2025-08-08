import React, { useState } from 'react';

const AdminPanel = ({ apiEndpoint = '/api/admin.php' }) => {
  const [stats, setStats] = useState({
    todayTotal: 0,
    currentWaiting: 0,
    averageWaitTime: 0,
    servedToday: 0
  });

  React.useEffect(() => {
    fetchStats();
  }, []);

  const fetchStats = async () => {
    try {
      const response = await fetch(`${apiEndpoint}?action=stats`);
      const data = await response.json();
      setStats(data);
    } catch (error) {
      console.error('Error:', error);
    }
  };

  const clearQueue = async () => {
    if (!confirm('Are you sure you want to clear the entire queue?')) return;
    
    try {
      await fetch(`${apiEndpoint}?action=clear`, { method: 'POST' });
      fetchStats();
    } catch (error) {
      console.error('Error:', error);
    }
  };

  return (
    <div style={{
      backgroundColor: 'white',
      borderRadius: '12px',
      padding: '24px',
      boxShadow: '0 4px 6px rgba(0,0,0,0.1)'
    }}>
      <h2 style={{ marginTop: 0, marginBottom: '20px', color: '#1f2937' }}>
        🎛️ Admin Panel
      </h2>
      
      <div style={{
        display: 'grid',
        gridTemplateColumns: 'repeat(auto-fit, minmax(150px, 1fr))',
        gap: '16px',
        marginBottom: '24px'
      }}>
        <div style={{ textAlign: 'center', padding: '16px', backgroundColor: '#eff6ff', borderRadius: '8px' }}>
          <div style={{ fontSize: '32px', fontWeight: 'bold', color: '#3b82f6' }}>{stats.todayTotal}</div>
          <div style={{ fontSize: '14px', color: '#6b7280' }}>Total Today</div>
        </div>
        <div style={{ textAlign: 'center', padding: '16px', backgroundColor: '#fef3c7', borderRadius: '8px' }}>
          <div style={{ fontSize: '32px', fontWeight: 'bold', color: '#f59e0b' }}>{stats.currentWaiting}</div>
          <div style={{ fontSize: '14px', color: '#6b7280' }}>Waiting</div>
        </div>
        <div style={{ textAlign: 'center', padding: '16px', backgroundColor: '#dcfce7', borderRadius: '8px' }}>
          <div style={{ fontSize: '32px', fontWeight: 'bold', color: '#10b981' }}>{stats.servedToday}</div>
          <div style={{ fontSize: '14px', color: '#6b7280' }}>Served</div>
        </div>
        <div style={{ textAlign: 'center', padding: '16px', backgroundColor: '#fce7f3', borderRadius: '8px' }}>
          <div style={{ fontSize: '32px', fontWeight: 'bold', color: '#ec4899' }}>{stats.averageWaitTime}m</div>
          <div style={{ fontSize: '14px', color: '#6b7280' }}>Avg Wait</div>
        </div>
      </div>
      
      <div style={{ display: 'flex', gap: '12px' }}>
        <button onClick={clearQueue} style={{
          padding: '10px 20px',
          backgroundColor: '#ef4444',
          color: 'white',
          border: 'none',
          borderRadius: '6px',
          cursor: 'pointer'
        }}>
          Clear Queue
        </button>
        <button onClick={fetchStats} style={{
          padding: '10px 20px',
          backgroundColor: '#3b82f6',
          color: 'white',
          border: 'none',
          borderRadius: '6px',
          cursor: 'pointer'
        }}>
          Refresh Stats
        </button>
      </div>
    </div>
  );
};

export default AdminPanel;
