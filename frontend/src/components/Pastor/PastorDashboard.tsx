import React, { useState, useEffect } from 'react';
import { 
  Upload, 
  FileAudio, 
  FileVideo, 
  Image, 
  Plus, 
  Users, 
  MessageSquare, 
  Bell,
  Settings,
  LogOut,
  Church,
  Mic,
  Camera,
  Send
} from 'lucide-react';
import { toast } from '@/hooks/use-toast';

interface PastorDashboardProps {
  onLogout: () => void;
}

const PastorDashboard: React.FC<PastorDashboardProps> = ({ onLogout }) => {
  const [activeTab, setActiveTab] = useState('sermons');
  const [sermons, setSermons] = useState([]);
  const [gallery, setGallery] = useState([]);
  const [messages, setMessages] = useState([]);
  const [notifications, setNotifications] = useState([]);
  const [isLoading, setIsLoading] = useState(false);

  // Form states
  const [sermonForm, setSermonForm] = useState({
    title: '',
    description: '',
    content: '',
    audioFile: null,
    videoFile: null,
    imageFile: null
  });

  const [galleryForm, setGalleryForm] = useState({
    title: '',
    description: '',
    category: 'general',
    imageFile: null,
    videoFile: null
  });

  const [messageForm, setMessageForm] = useState({
    receiverId: '',
    subject: '',
    message: ''
  });

  useEffect(() => {
    fetchDashboardData();
  }, []);

  const fetchDashboardData = async () => {
    try {
      const [sermonsRes, galleryRes, messagesRes, notificationsRes] = await Promise.all([
        fetch('/api/sermons'),
        fetch('/api/gallery'),
        fetch('/api/messages'),
        fetch('/api/notifications')
      ]);

      const [sermonsData, galleryData, messagesData, notificationsData] = await Promise.all([
        sermonsRes.json(),
        galleryRes.json(),
        messagesRes.json(),
        notificationsRes.json()
      ]);

      if (sermonsData.success) setSermons(sermonsData.data || []);
      if (galleryData.success) setGallery(galleryData.data || []);
      if (messagesData.success) setMessages(messagesData.data || []);
      if (notificationsData.success) setNotifications(notificationsData.data || []);
    } catch (error) {
      console.error('Error fetching dashboard data:', error);
    }
  };

  const handleSermonSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    try {
      const formData = new FormData();
      formData.append('title', sermonForm.title);
      formData.append('description', sermonForm.description);
      formData.append('content', sermonForm.content);
      
      if (sermonForm.audioFile) formData.append('audio', sermonForm.audioFile);
      if (sermonForm.videoFile) formData.append('video', sermonForm.videoFile);
      if (sermonForm.imageFile) formData.append('image', sermonForm.imageFile);

      const response = await fetch('/api/sermons', {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      if (data.success) {
        toast({
          title: "Sermon Posted Successfully",
          description: "Your sermon has been published and members will be notified.",
        });
        
        setSermonForm({
          title: '',
          description: '',
          content: '',
          audioFile: null,
          videoFile: null,
          imageFile: null
        });
        
        fetchDashboardData();
      } else {
        toast({
          title: "Error",
          description: data.message || "Failed to post sermon",
          variant: "destructive"
        });
      }
    } catch (error) {
      toast({
        title: "Error",
        description: "Network error occurred",
        variant: "destructive"
      });
    } finally {
      setIsLoading(false);
    }
  };

  const handleGallerySubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    try {
      const formData = new FormData();
      formData.append('title', galleryForm.title);
      formData.append('description', galleryForm.description);
      formData.append('category', galleryForm.category);
      
      if (galleryForm.imageFile) formData.append('image', galleryForm.imageFile);
      if (galleryForm.videoFile) formData.append('video', galleryForm.videoFile);

      const response = await fetch('/api/gallery', {
        method: 'POST',
        body: formData
      });

      const data = await response.json();

      if (data.success) {
        toast({
          title: "Gallery Item Added",
          description: "Your media has been uploaded successfully.",
        });
        
        setGalleryForm({
          title: '',
          description: '',
          category: 'general',
          imageFile: null,
          videoFile: null
        });
        
        fetchDashboardData();
      } else {
        toast({
          title: "Error",
          description: data.message || "Failed to upload media",
          variant: "destructive"
        });
      }
    } catch (error) {
      toast({
        title: "Error",
        description: "Network error occurred",
        variant: "destructive"
      });
    } finally {
      setIsLoading(false);
    }
  };

  const handleMessageSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    try {
      const response = await fetch('/api/messages', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(messageForm)
      });

      const data = await response.json();

      if (data.success) {
        toast({
          title: "Message Sent",
          description: "Your message has been delivered successfully.",
        });
        
        setMessageForm({
          receiverId: '',
          subject: '',
          message: ''
        });
        
        fetchDashboardData();
      } else {
        toast({
          title: "Error",
          description: data.message || "Failed to send message",
          variant: "destructive"
        });
      }
    } catch (error) {
      toast({
        title: "Error",
        description: "Network error occurred",
        variant: "destructive"
      });
    } finally {
      setIsLoading(false);
    }
  };

  const tabs = [
    { id: 'sermons', label: 'Sermons', icon: Mic },
    { id: 'gallery', label: 'Gallery', icon: Camera },
    { id: 'messages', label: 'Messages', icon: MessageSquare },
    { id: 'notifications', label: 'Notifications', icon: Bell },
    { id: 'members', label: 'Members', icon: Users },
    { id: 'settings', label: 'Settings', icon: Settings }
  ];

  return (
    <div className="min-h-screen bg-navy">
      {/* Header */}
      <header className="bg-navy/90 backdrop-blur-sm border-b border-gold/20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between h-16">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-gradient-gold rounded-full flex items-center justify-center">
                <Church className="w-5 h-5 text-white" />
              </div>
              <div>
                <h1 className="text-xl font-bold text-white">Pastor Dashboard</h1>
                <p className="text-gold/80 text-sm">Apostle Faty Musasizi</p>
              </div>
            </div>
            
            <button
              onClick={onLogout}
              className="flex items-center gap-2 px-4 py-2 text-gold hover:text-gold/80 transition"
            >
              <LogOut className="w-4 h-4" />
              Logout
            </button>
          </div>
        </div>
      </header>

      <div className="flex">
        {/* Sidebar */}
        <aside className="w-64 bg-navy/80 backdrop-blur-sm border-r border-gold/20 min-h-screen">
          <nav className="p-4 space-y-2">
            {tabs.map((tab) => (
              <button
                key={tab.id}
                onClick={() => setActiveTab(tab.id)}
                className={`w-full flex items-center gap-3 px-4 py-3 rounded-lg transition ${
                  activeTab === tab.id
                    ? 'bg-gold/20 text-gold'
                    : 'text-white/70 hover:text-white hover:bg-white/10'
                }`}
              >
                <tab.icon className="w-5 h-5" />
                {tab.label}
              </button>
            ))}
          </nav>
        </aside>

        {/* Main Content */}
        <main className="flex-1 p-6">
          {/* Sermons Tab */}
          {activeTab === 'sermons' && (
            <div className="space-y-6">
              <div className="glassmorphism-enhanced rounded-2xl p-6">
                <h2 className="text-2xl font-bold text-white mb-6">Post New Sermon</h2>
                
                <form onSubmit={handleSermonSubmit} className="space-y-4">
                  <div>
                    <label className="block text-sm font-medium text-gold mb-2">Sermon Title</label>
                    <input
                      type="text"
                      value={sermonForm.title}
                      onChange={(e) => setSermonForm({...sermonForm, title: e.target.value})}
                      className="w-full px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg text-white placeholder-gold/50 focus:outline-none focus:ring-2 focus:ring-gold"
                      placeholder="Enter sermon title"
                      required
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gold mb-2">Description</label>
                    <textarea
                      value={sermonForm.description}
                      onChange={(e) => setSermonForm({...sermonForm, description: e.target.value})}
                      className="w-full px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg text-white placeholder-gold/50 focus:outline-none focus:ring-2 focus:ring-gold"
                      rows={3}
                      placeholder="Brief description of the sermon"
                      required
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gold mb-2">Full Content</label>
                    <textarea
                      value={sermonForm.content}
                      onChange={(e) => setSermonForm({...sermonForm, content: e.target.value})}
                      className="w-full px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg text-white placeholder-gold/50 focus:outline-none focus:ring-2 focus:ring-gold"
                      rows={6}
                      placeholder="Full sermon content or transcript"
                      required
                    />
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                      <label className="block text-sm font-medium text-gold mb-2">Audio File</label>
                      <div className="relative">
                        <input
                          type="file"
                          accept="audio/*"
                          onChange={(e) => setSermonForm({...sermonForm, audioFile: e.target.files?.[0] || null})}
                          className="hidden"
                          id="audio-file"
                        />
                        <label
                          htmlFor="audio-file"
                          className="flex items-center gap-2 px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg cursor-pointer hover:bg-navy/70 transition"
                        >
                          <FileAudio className="w-4 h-4 text-gold" />
                          <span className="text-white">
                            {sermonForm.audioFile ? sermonForm.audioFile.name : 'Choose audio file'}
                          </span>
                        </label>
                      </div>
                    </div>

                    <div>
                      <label className="block text-sm font-medium text-gold mb-2">Video File</label>
                      <div className="relative">
                        <input
                          type="file"
                          accept="video/*"
                          onChange={(e) => setSermonForm({...sermonForm, videoFile: e.target.files?.[0] || null})}
                          className="hidden"
                          id="video-file"
                        />
                        <label
                          htmlFor="video-file"
                          className="flex items-center gap-2 px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg cursor-pointer hover:bg-navy/70 transition"
                        >
                          <FileVideo className="w-4 h-4 text-gold" />
                          <span className="text-white">
                            {sermonForm.videoFile ? sermonForm.videoFile.name : 'Choose video file'}
                          </span>
                        </label>
                      </div>
                    </div>

                    <div>
                      <label className="block text-sm font-medium text-gold mb-2">Cover Image</label>
                      <div className="relative">
                        <input
                          type="file"
                          accept="image/*"
                          onChange={(e) => setSermonForm({...sermonForm, imageFile: e.target.files?.[0] || null})}
                          className="hidden"
                          id="image-file"
                        />
                        <label
                          htmlFor="image-file"
                          className="flex items-center gap-2 px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg cursor-pointer hover:bg-navy/70 transition"
                        >
                          <Image className="w-4 h-4 text-gold" />
                          <span className="text-white">
                            {sermonForm.imageFile ? sermonForm.imageFile.name : 'Choose image'}
                          </span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <button
                    type="submit"
                    disabled={isLoading}
                    className="w-full bg-gradient-gold text-primary py-3 rounded-lg font-semibold hover:opacity-90 transition disabled:opacity-50"
                  >
                    {isLoading ? 'Posting...' : 'Post Sermon'}
                  </button>
                </form>
              </div>

              {/* Recent Sermons */}
              <div className="glassmorphism-enhanced rounded-2xl p-6">
                <h3 className="text-xl font-bold text-white mb-4">Recent Sermons</h3>
                <div className="space-y-3">
                  {sermons.map((sermon: { id: number; title: string; description: string }) => (
                    <div key={sermon.id} className="bg-navy/50 rounded-lg p-4 border border-gold/20">
                      <h4 className="font-semibold text-white">{sermon.title}</h4>
                      <p className="text-gold/80 text-sm">{sermon.description}</p>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          )}

          {/* Gallery Tab */}
          {activeTab === 'gallery' && (
            <div className="space-y-6">
              <div className="glassmorphism-enhanced rounded-2xl p-6">
                <h2 className="text-2xl font-bold text-white mb-6">Upload Media</h2>
                
                <form onSubmit={handleGallerySubmit} className="space-y-4">
                  <div>
                    <label className="block text-sm font-medium text-gold mb-2">Title</label>
                    <input
                      type="text"
                      value={galleryForm.title}
                      onChange={(e) => setGalleryForm({...galleryForm, title: e.target.value})}
                      className="w-full px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg text-white placeholder-gold/50 focus:outline-none focus:ring-2 focus:ring-gold"
                      placeholder="Media title"
                      required
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gold mb-2">Description</label>
                    <textarea
                      value={galleryForm.description}
                      onChange={(e) => setGalleryForm({...galleryForm, description: e.target.value})}
                      className="w-full px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg text-white placeholder-gold/50 focus:outline-none focus:ring-2 focus:ring-gold"
                      rows={3}
                      placeholder="Describe this media"
                      required
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gold mb-2">Category</label>
                    <select
                      value={galleryForm.category}
                      onChange={(e) => setGalleryForm({...galleryForm, category: e.target.value})}
                      className="w-full px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-gold"
                    >
                      <option value="general">General</option>
                      <option value="events">Events</option>
                      <option value="worship">Worship</option>
                      <option value="outreach">Outreach</option>
                      <option value="youth">Youth</option>
                    </select>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-sm font-medium text-gold mb-2">Image File</label>
                      <div className="relative">
                        <input
                          type="file"
                          accept="image/*"
                          onChange={(e) => setGalleryForm({...galleryForm, imageFile: e.target.files?.[0] || null})}
                          className="hidden"
                          id="gallery-image"
                        />
                        <label
                          htmlFor="gallery-image"
                          className="flex items-center gap-2 px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg cursor-pointer hover:bg-navy/70 transition"
                        >
                          <Image className="w-4 h-4 text-gold" />
                          <span className="text-white">
                            {galleryForm.imageFile ? galleryForm.imageFile.name : 'Choose image'}
                          </span>
                        </label>
                      </div>
                    </div>

                    <div>
                      <label className="block text-sm font-medium text-gold mb-2">Video File</label>
                      <div className="relative">
                        <input
                          type="file"
                          accept="video/*"
                          onChange={(e) => setGalleryForm({...galleryForm, videoFile: e.target.files?.[0] || null})}
                          className="hidden"
                          id="gallery-video"
                        />
                        <label
                          htmlFor="gallery-video"
                          className="flex items-center gap-2 px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg cursor-pointer hover:bg-navy/70 transition"
                        >
                          <FileVideo className="w-4 h-4 text-gold" />
                          <span className="text-white">
                            {galleryForm.videoFile ? galleryForm.videoFile.name : 'Choose video'}
                          </span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <button
                    type="submit"
                    disabled={isLoading}
                    className="w-full bg-gradient-gold text-primary py-3 rounded-lg font-semibold hover:opacity-90 transition disabled:opacity-50"
                  >
                    {isLoading ? 'Uploading...' : 'Upload Media'}
                  </button>
                </form>
              </div>
            </div>
          )}

          {/* Messages Tab */}
          {activeTab === 'messages' && (
            <div className="space-y-6">
              <div className="glassmorphism-enhanced rounded-2xl p-6">
                <h2 className="text-2xl font-bold text-white mb-6">Send Message</h2>
                
                <form onSubmit={handleMessageSubmit} className="space-y-4">
                  <div>
                    <label className="block text-sm font-medium text-gold mb-2">Recipient</label>
                    <select
                      value={messageForm.receiverId}
                      onChange={(e) => setMessageForm({...messageForm, receiverId: e.target.value})}
                      className="w-full px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-gold"
                      required
                    >
                      <option value="">Select a member</option>
                      {/* This would be populated with actual members */}
                    </select>
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gold mb-2">Subject</label>
                    <input
                      type="text"
                      value={messageForm.subject}
                      onChange={(e) => setMessageForm({...messageForm, subject: e.target.value})}
                      className="w-full px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg text-white placeholder-gold/50 focus:outline-none focus:ring-2 focus:ring-gold"
                      placeholder="Message subject"
                      required
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gold mb-2">Message</label>
                    <textarea
                      value={messageForm.message}
                      onChange={(e) => setMessageForm({...messageForm, message: e.target.value})}
                      className="w-full px-4 py-2 bg-navy/50 border border-gold/30 rounded-lg text-white placeholder-gold/50 focus:outline-none focus:ring-2 focus:ring-gold"
                      rows={6}
                      placeholder="Type your message here..."
                      required
                    />
                  </div>

                  <button
                    type="submit"
                    disabled={isLoading}
                    className="w-full bg-gradient-gold text-primary py-3 rounded-lg font-semibold hover:opacity-90 transition disabled:opacity-50 flex items-center justify-center gap-2"
                  >
                    <Send className="w-4 h-4" />
                    {isLoading ? 'Sending...' : 'Send Message'}
                  </button>
                </form>
              </div>

              {/* Recent Messages */}
              <div className="glassmorphism-enhanced rounded-2xl p-6">
                <h3 className="text-xl font-bold text-white mb-4">Recent Messages</h3>
                <div className="space-y-3">
                  {messages.map((message: { id: number; subject: string; message: string; created_at: string }) => (
                    <div key={message.id} className="bg-navy/50 rounded-lg p-4 border border-gold/20">
                      <div className="flex items-center justify-between mb-2">
                        <h4 className="font-semibold text-white">{message.subject}</h4>
                        <span className="text-gold/80 text-sm">
                          {new Date(message.created_at).toLocaleDateString()}
                        </span>
                      </div>
                      <p className="text-gold/80">{message.message}</p>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          )}
        </main>
      </div>
    </div>
  );
};

export default PastorDashboard;
